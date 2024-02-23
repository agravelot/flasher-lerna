package app

import (
	"api-go/domain/media"
	"api-go/infrastructure/auth"
	"api-go/infrastructure/openapi"
	"api-go/infrastructure/s3"
	"api-go/model"
	"api-go/uploads"
	"context"
	"errors"
	"fmt"
	"github.com/aws/aws-sdk-go-v2/service/s3/types"
	"github.com/google/uuid"
	"github.com/kr/pretty"
	"github.com/minio/minio-go/v7"
	"image"
	"log"
	"net"
	"net/http"
	"net/url"
	"os"
	"os/signal"
	"strconv"
	"strings"
	"syscall"
	"time"

	"github.com/grpc-ecosystem/grpc-gateway/v2/runtime"
	"google.golang.org/grpc"
	"google.golang.org/grpc/credentials/insecure"
	"google.golang.org/grpc/grpclog"

	"api-go/config"
	"api-go/domain/album"
	"api-go/domain/article"
	albumsPb "api-go/gen/go/proto/albums/v2"
	articlesPb "api-go/gen/go/proto/articles/v2"
	mediaPb "api-go/gen/go/proto/medias/v2"
	"api-go/infrastructure/storage/postgres"
	grpcAuth "github.com/grpc-ecosystem/go-grpc-middleware/auth"
	_ "image/jpeg"
)

// Run creates objects via constructors.
func Run(config *config.Config) error {

	// var logger log.Logger
	// {
	// 	logger = log.NewLogfmtLogger(os.Stderr)
	// 	logger = log.With(logger, "ts", log.DefaultTimestampUTC)
	// 	logger = log.With(logger, "caller", log.DefaultCaller)
	// }

	orm, err := postgres.New(config.Database.URL)
	if err != nil {
		return fmt.Errorf("could not connect to database: %w", err)
	}
	defer func(orm postgres.Postgres) {
		err := orm.Close()
		if err != nil {
			log.Printf("unable to close database connection: %v", err)
		}
	}(orm)

	albumRepo, err := postgres.NewAlbumRepository(&orm)
	if err != nil {
		return fmt.Errorf("unable to get album repository")
	}

	sAlbum, err := album.NewService(albumRepo)
	if err != nil {
		return fmt.Errorf("could not create album service: %w", err)
	}

	mediaRepo, err := postgres.NewMediaRepository(&orm)
	if err != nil {
		return fmt.Errorf("unable to get media repository")
	}

	sMedia, err := media.NewService(mediaRepo)
	if err != nil {
		return fmt.Errorf("could not create album service: %w", err)
	}

	articleRepo, err := postgres.NewArticleRepository(&orm)
	if err != nil {
		return err
	}

	sArticle := article.NewService(articleRepo)

	// Adds gRPC internal logs. This is quite verbose, so adjust as desired!
	l := grpclog.NewLoggerV2(os.Stdout, os.Stdout, os.Stdout)
	grpclog.SetLoggerV2(l)

	// Create a listener on TCP port
	lis, err := net.Listen("tcp", fmt.Sprintf(":%d", config.AppGrpcPort))
	if err != nil {
		return fmt.Errorf("failed to listen: %w", err)
	}

	// Create a gRPC server object
	grpcServer := grpc.NewServer(
		grpc.StreamInterceptor(grpcAuth.StreamServerInterceptor(auth.GrpcInterceptor)),
		grpc.UnaryInterceptor(grpcAuth.UnaryServerInterceptor(auth.GrpcInterceptor)),
	)

	// Attach the services to the server
	articlesPb.RegisterArticleServiceServer(grpcServer, sArticle)
	albumsPb.RegisterAlbumServiceServer(grpcServer, sAlbum)
	mediaPb.RegisterMediaServiceServer(grpcServer, sMedia)
	// Serve gRPC server
	go func() {
		log.Printf(
			"Serving gRPC on 0.0.0.0:%d", config.AppGrpcPort)
		log.Fatalln(grpcServer.Serve(lis))
	}()

	// Create a client connection to the gRPC server we just started
	// This is where the gRPC-Gateway proxies the requests
	conn, err := grpc.DialContext(
		context.Background(),
		// TODO Can configure the address of the gRPC server
		fmt.Sprintf("0.0.0.0:%d", config.AppGrpcPort),
		grpc.WithBlock(),
		grpc.WithTransportCredentials(insecure.NewCredentials()),
	)

	if err != nil {
		return fmt.Errorf("failed to dial server: %w", err)
	}

	gwmux := runtime.NewServeMux()
	err = articlesPb.RegisterArticleServiceHandler(context.Background(), gwmux, conn)
	if err != nil {
		return fmt.Errorf("failed to register article gateway: %w", err)
	}

	err = albumsPb.RegisterAlbumServiceHandler(context.Background(), gwmux, conn)
	if err != nil {
		return fmt.Errorf("failed to register albums gateway: %w", err)
	}

	err = mediaPb.RegisterMediaServiceHandler(context.Background(), gwmux, conn)
	if err != nil {
		return fmt.Errorf("failed to register media gateway: %w", err)
	}

	err = openapi.New("/", gwmux)
	if err != nil {
		return fmt.Errorf("unable to init openapi for application: %w", err)
	}

	var onEvent uploads.OnUploadedFileMessage = func(file uploads.UploadedFile) error {
		pretty.Logf("Uploading %d file", len(file.Records))

		// TODO Return error ?
		for _, record := range file.Records {
			pretty.Log(record.S3.Object.Key)
			key, err := url.QueryUnescape(record.S3.Object.Key)
			if err != nil {
				pretty.Log("unable unquote key: %w", err)

				continue
			}

			if record.S3.Bucket.Name != "temp" {
				pretty.Log("skipping object not from temp bucket")

				continue
			}

			if record.EventName != types.EventS3ObjectCreatedPut {
				pretty.Log("skipping non put event")

				continue
			}

			if !strings.HasPrefix(record.S3.Object.ContentType, "image/") {
				pretty.Logf("skipping event with content type: %s", record.S3.Object.ContentType)

				continue
			}

			// TODO Check size

			if !strings.HasPrefix(key, "albums/") {
				pretty.Logf("skipping event with key: %s", key)

				continue
			}

			// TODO extract in func
			splitPath := strings.Split(key, "/")
			pretty.Log(splitPath)

			const expectedKeyPathDepth = 3
			if len(splitPath) != expectedKeyPathDepth {
				pretty.Log("skipping event with invalid key: %s", key)

				continue
			}

			albumID, err := strconv.Atoi(splitPath[1])
			if err != nil {
				pretty.Logf("skipping event with invalid id: %s", key)

				continue
			}

			pretty.Logf("get new album event with id %d", albumID)

			_, err = albumRepo.GetByID(context.TODO(), album.GetByIDParams{
				ID:             int32(albumID),
				IncludePrivate: true,
			})

			if errors.Is(err, album.ErrNotFound) {
				log.Printf("album with id=%d not found", albumID)

				continue
			}

			if err != nil {
				log.Printf("failed to get album id=%d", albumID)

				continue
			}

			tempS3Client, err := s3.NewClient()

			if err != nil {
				return fmt.Errorf("failed create new s3 client: %w", err)
			}

			reader, err := tempS3Client.GetObject(context.TODO(), "temp", key, minio.GetObjectOptions{})
			if err != nil {
				return fmt.Errorf("failed get remote file: %w", err)
			}
			defer reader.Close()

			imageConfig, imageType, err := image.DecodeConfig(reader)
			if err != nil {
				log.Fatalln(err)
			}

			if imageType != "jpeg" {
				return fmt.Errorf("invalid image type, got=%s", imageType)
			}

			res, err := tempS3Client.PutObject(
				context.TODO(),
				"final",
				key,
				reader,
				int64(record.S3.Object.Size),
				minio.PutObjectOptions{},
			)
			if err != nil {
				return fmt.Errorf("failed get remote file: %w", err)
			}

			pretty.Log(res)

			disk := "s3"
			now := time.Now()
			customProperties := model.CustomProperties{
				Height: int32(imageConfig.Height),
				Width:  int32(imageConfig.Width),
			}
			ri := model.ResponsiveImages{}
			uuidString := uuid.New().String()

			// 1943,App\Models\Album,54,pictures,show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3.jpg,show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3.jpg,image/jpeg,s3,17239857,[],"{""generated_conversions"":{""responsive"":true,""thumb"":true},""width"":5897,""height"":3317}","{""responsive"":{""urls"":[""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_5897_3317.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_4933_2774.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_4127_2321.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_3453_1942.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_2889_1625.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_2417_1359.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_2022_1137.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_1692_951.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_1415_795.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_1184_665.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_991_557.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_829_466.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_693_389.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_580_326.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_485_272.jpg"",""show-disney-jth-lyon-2019_be09b419-2bc5-482f-8265-9fcba50b23d3___responsive_406_228.jpg""],""base64svg"":""data:image\/svg+xml;base64,PCFET0NUWVBFIHN2ZyBQVUJMSUMgIi0vL1czQy8vRFREIFNWRyAxLjEvL0VOIiAiaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkIj4KPHN2ZyB2ZXJzaW9uPSIxLjEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHg9IjAiCiB5PSIwIiB2aWV3Qm94PSIwIDAgNTg5NyAzMzE3Ij4KCTxpbWFnZSB3aWR0aD0iNTg5NyIgaGVpZ2h0PSIzMzE3IiB4bGluazpocmVmPSJkYXRhOmltYWdlL2pwZWc7YmFzZTY0LC85ai80QUFRU2taSlJnQUJBUUFBQVFBQkFBRC8yd0JEQUFNQ0FnTUNBZ01EQXdNRUF3TUVCUWdGQlFRRUJRb0hCd1lJREFvTURBc0tDd3NORGhJUURRNFJEZ3NMRUJZUUVSTVVGUlVWREE4WEdCWVVHQklVRlJULzJ3QkRBUU1FQkFVRUJRa0ZCUWtVRFFzTkZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlFVRkJRVUZCUVVGQlQvd0FBUkNBQVJBQjREQVJFQUFoRUJBeEVCLzhRQUZ3QUFBd0VBQUFBQUFBQUFBQUFBQUFBQUJBVUdDUC9FQUNVUUFBRURBd0lHQXdBQUFBQUFBQUFBQUFFQ0F3VUFCeEVFQmhJaE1qTlJjVFZCWWYvRUFCc0JBQUlDQXdFQUFBQUFBQUFBQUFBQUFBSUZBd1lBQkFjQi84UUFJeEVBQWdJQkF3TUZBQUFBQUFBQUFBQUFBQUVDQXdRU0lURVJFMEVGRkNJeVF2L2FBQXdEQVFBQ0VRTVJBRDhBd0xzKzNPcm4zUWdORWtIeFRSMnFDM0JvcjF5Um91M2RvMkl0bEoxaUFuM1dsTFBueEZuVHZUSTFWdytSWFRWdjRoNW5MWVFjVnArNnNiM1k5bGJqTUNqZHFSV2xhS1ZKUUtLenVUM1RBZWZqMHJva1NGdkpMUVFNdzZseEtjQThzMDNzcTdxYVJ3NnF5VUdocnZTNTZHSGxOc0hoVDlZcFJERmxHVzViNmN6cERrU1E5eDFMWVVISE01L2E4dW9sK1E0NWVwN3NGbHQrSkFIQTVqbjVySWE0Yk15MjJMOGs4ajV4ZnVyRFNVN3lMTjRkNmdmSXloOVJYRzlCcUo4a2tBV1c2aFVGbkljei85az0iPgoJPC9pbWFnZT4KPC9zdmc+""}}",9,2019-06-10 15:22:05,2020-10-09 22:06:37,f0f856c4-6c11-4198-a916-dfe4bd63df01,s3
			medium, err := mediaRepo.Create(context.TODO(), media.CreateParams{
				Media: model.Medium{
					ModelType:      "App\\Models\\Album",
					ModelID:        int64(albumID),
					CollectionName: "pictures",
					Name:           "toto.jpeg", // TODO
					FileName:       "toto.jpeg", // TODO
					MimeType:       &record.S3.Object.ContentType,
					Disk:           disk,
					Size:           int64(record.S3.Object.Size),
					// TODO Custom properties
					CustomProperties: &customProperties,
					UUID:             &uuidString,
					// TODO order
					CreatedAt:       &now,
					UpdatedAt:       &now,
					ConversionsDisk: &disk,
					// TODO Empty array
					ResponsiveImages: &ri,
					Manipulations:    "[]",
				},
			})

			pretty.Log(medium, err)

		}

		return nil
	}

	err = uploads.NewEventListener(onEvent)
	if err != nil {
		pretty.Log(err)
	}

	gwServer := &http.Server{
		Addr:              fmt.Sprintf("0.0.0.0:%d", config.AppHttpPort),
		Handler:           gwmux,
		ReadHeaderTimeout: 2 * time.Second,
	}

	go func() {
		sigquit := make(chan os.Signal, 1)
		signal.Notify(sigquit, os.Interrupt, syscall.SIGTERM)

		sig := <-sigquit
		log.Printf("caught sig: %+v\n", sig)
		log.Println("gracefully shuting down servers...")

		err := gwServer.Shutdown(context.Background())
		if err != nil {
			log.Printf("unable to shut down http sever: %v\n", err)
		}
		log.Println("http server stopped")

		grpcServer.GracefulStop()
		log.Println("grpc server stopped")

		err = lis.Close()
		if err != nil {
			log.Printf("unable to close socket: %v\n", err)
		}
	}()

	log.Printf("Serving gRPC-Gateway on http://0.0.0.0:%d | http://127.0.0.1:%d\n", config.AppHttpPort, config.AppHttpPort)
	err = gwServer.ListenAndServe()
	if err != nil {
		log.Fatalln(gwServer.ListenAndServe())
	}

	return nil
}
