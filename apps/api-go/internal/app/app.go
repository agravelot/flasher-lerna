package app

import (
	"api-go/config"
	"api-go/domain/album"
	"api-go/domain/article"
	"api-go/domain/media"
	"api-go/infrastructure/auth"
	"api-go/infrastructure/openapi"
	"api-go/infrastructure/s3"
	"api-go/infrastructure/storage/postgres"
	"api-go/model"
	"api-go/uploads"
	"context"
	"errors"
	"fmt"
	"image"
	_ "image/jpeg"
	_ "image/png"
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

	"github.com/aws/aws-sdk-go-v2/service/s3/types"
	"github.com/google/uuid"
	grpcAuth "github.com/grpc-ecosystem/go-grpc-middleware/auth"
	"github.com/grpc-ecosystem/grpc-gateway/v2/runtime"
	"github.com/kr/pretty"
	"github.com/minio/minio-go/v7"
	"google.golang.org/grpc"
	"google.golang.org/grpc/credentials/insecure"
	"google.golang.org/grpc/grpclog"
	"google.golang.org/grpc/reflection"

	albumsPb "api-go/gen/go/albums/v2"
	articlesPb "api-go/gen/go/articles/v2"
	mediaPb "api-go/gen/go/medias/v2"
)

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
		return fmt.Errorf("unable to get album repository: %w", err)
	}

	sAlbum, err := album.NewService(albumRepo)
	if err != nil {
		return fmt.Errorf("could not create album service: %w", err)
	}

	mediaRepo, err := postgres.NewMediaRepository(&orm)
	if err != nil {
		return fmt.Errorf("unable to get media repository: %w", err)
	}

	sMedia, err := media.NewService(mediaRepo)
	if err != nil {
		return fmt.Errorf("could not create album service: %w", err)
	}

	articleRepo, err := postgres.NewArticleRepository(&orm)
	if err != nil {
		return fmt.Errorf("unable to create articlre repository: %w", err)
	}

	sArticle := article.NewService(articleRepo)

	l := grpclog.NewLoggerV2(os.Stdout, os.Stdout, os.Stdout)
	grpclog.SetLoggerV2(l)

	lis, err := net.Listen("tcp", fmt.Sprintf(":%d", config.AppGrpcPort))
	if err != nil {
		return fmt.Errorf("failed to listen: %w", err)
	}

	grpcServer := grpc.NewServer(
		grpc.StreamInterceptor(grpcAuth.StreamServerInterceptor(auth.GrpcInterceptor)),
		grpc.UnaryInterceptor(grpcAuth.UnaryServerInterceptor(auth.GrpcInterceptor)),
	)

	articlesPb.RegisterArticleServiceServer(grpcServer, sArticle)
	albumsPb.RegisterAlbumServiceServer(grpcServer, sAlbum)
	mediaPb.RegisterMediaServiceServer(grpcServer, sMedia)

	err = registerUploadHandler(albumRepo, mediaRepo)
	if err != nil {
		return fmt.Errorf("failed to start event listener: %w", err)
	}

	// TODO Disable reflection in prod deployment
	reflection.Register(grpcServer)

	go func() {
		log.Printf(
			"Serving gRPC on 0.0.0.0:%d", config.AppGrpcPort)
		log.Fatalln(grpcServer.Serve(lis))
	}()

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

// TODO Move it once ready
func registerUploadHandler(albumRepo album.Repository, mediaRepo media.Repository) error {
	onEvent := func(file uploads.UploadedFile) error {
		pretty.Logf("Uploading %d file", len(file.Records))

		finalBucket := "final"
		// TODO Goroutine
		// TODO Return error ?
		for _, record := range file.Records {
			pretty.Log(record.S3.Object.Key)
			key, err := url.QueryUnescape(record.S3.Object.Key)
			if err != nil {
				log.Printf("unable unquote key: %v", err)

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
			// TODO strip file extension
			filename := splitPath[len(splitPath)-1]

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
				return fmt.Errorf("failed decode image config: %w", err)
			}

			// TODO list of supported image types
			if imageType != "jpeg" && imageType != "png" {
				return fmt.Errorf("invalid image type, got=%s", imageType)
			}

			res, err := tempS3Client.PutObject(
				context.TODO(),
				finalBucket,
				key,
				reader,
				int64(record.S3.Object.Size),
				minio.PutObjectOptions{},
			)
			if err != nil {
				return fmt.Errorf("failed get remote file: %w", err)
			}

			pretty.Log(res)

			// TODO extract in repo

			disk := "s3"
			now := time.Now()
			// TODO Custom properties
			customProperties := model.CustomProperties{
				Height: int32(imageConfig.Height),
				Width:  int32(imageConfig.Width),
			}
			uuidString := uuid.New().String()
			// TODO order
			order := int32(0)

			medium, err := mediaRepo.Create(context.TODO(), media.CreateParams{
				Media: model.Medium{
					ModelType:        "App\\Models\\Album",
					ModelID:          int64(albumID),
					CollectionName:   "pictures",
					Name:             filename,
					FileName:         filename,
					MimeType:         &record.S3.Object.ContentType,
					Disk:             disk,
					Size:             int64(record.S3.Object.Size),
					CustomProperties: &customProperties,
					UUID:             &uuidString,
					OrderColumn:      &order,
					CreatedAt:        &now,
					UpdatedAt:        &now,
					ConversionsDisk:  &disk,
					ResponsiveImages: "[]",
					Manipulations:    "[]",
				},
			})

			pretty.Log(medium)

			return err
		}
		return nil
	}

	err := uploads.NewEventListener(onEvent)
	if err != nil {
		pretty.Log(err)
	}
	return err
}
