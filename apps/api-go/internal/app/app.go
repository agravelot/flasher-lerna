package app

import (
	"context"
	"fmt"
	"io/fs"
	"log"
	"mime"
	"net"
	"net/http"
	"os"
	"strings"

	"github.com/grpc-ecosystem/grpc-gateway/v2/runtime"
	"google.golang.org/grpc"
	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/credentials/insecure"
	"google.golang.org/grpc/grpclog"
	"google.golang.org/grpc/status"

	"api-go/album"
	"api-go/article"
	"api-go/auth"
	"api-go/config"
	"api-go/database"
	albumspb "api-go/gen/go/proto/albums/v2"
	articlespb "api-go/gen/go/proto/articles/v2"
	"api-go/third_party"

	grpc_auth "github.com/grpc-ecosystem/go-grpc-middleware/auth"
	grpc_ctxtags "github.com/grpc-ecosystem/go-grpc-middleware/tags"
)

// getOpenAPIHandler serves an OpenAPI UI.
// Adapted from https://github.com/philips/grpc-gateway-example/blob/a269bcb5931ca92be0ceae6130ac27ae89582ecc/cmd/serve.go#L63
func getOpenAPIHandler() http.Handler {
	mime.AddExtensionType(".svg", "image/svg+xml")
	// Use subdirectory in embedded files
	subFS, err := fs.Sub(third_party.OpenAPI, "OpenAPI")
	if err != nil {
		panic("couldn't create sub filesystem: " + err.Error())
	}
	return http.FileServer(http.FS(subFS))
}

// TODO Extract to a separate file
// authFunc is used by a middleware to authenticate requests
func authFunc(ctx context.Context) (context.Context, error) {
	token, err := grpc_auth.AuthFromMD(ctx, "bearer")
	if err != nil {
		if status.Code(err) == codes.Unauthenticated {
			return ctx, nil
		}
		return ctx, err
	}

	parsedToken, err := auth.ParseToken(token)
	if err != nil {
		return ctx, status.Errorf(codes.Unauthenticated, "invalid auth token: %v", err)
	}

	grpc_ctxtags.Extract(ctx).Set("user", parsedToken)

	// WARNING: in production define your own type to avoid context collisions
	newCtx := context.WithValue(ctx, "user", parsedToken)

	return newCtx, nil
}

// Run creates objects via constructors.
func Run(config *config.Configurations) error {

	// var logger log.Logger
	// {
	// 	logger = log.NewLogfmtLogger(os.Stderr)
	// 	logger = log.With(logger, "ts", log.DefaultTimestampUTC)
	// 	logger = log.With(logger, "caller", log.DefaultCaller)
	// }

	orm, err := database.Init(config)
	if err != nil {
		return fmt.Errorf("could not connect to database: %w", err)
	}

	db, err := orm.DB()
	if err != nil {
		return fmt.Errorf("could not get DB: %w", err)
	}
	defer db.Close()

	var sAlbum albumspb.AlbumServiceServer
	{
		sAlbum = album.NewService(orm)
		// sAlbum = album.LoggingMiddleware(logger)(sAlbum)
	}

	var sArticle articlespb.ArticleServiceServer
	{
		sArticle = article.NewService(orm)
		// sArticle = article.LoggingMiddleware(logger)(sArticle)
	}

	// Adds gRPC internal logs. This is quite verbose, so adjust as desired!
	l := grpclog.NewLoggerV2(os.Stdout, os.Stdout, os.Stdout)
	grpclog.SetLoggerV2(l)

	// Create a listener on TCP port
	lis, err := net.Listen("tcp", fmt.Sprintf(":%d", config.AppGrpcPort))
	if err != nil {
		return fmt.Errorf("failed to listen: %w", err)
	}

	// Create a gRPC server object
	s := grpc.NewServer(
		grpc.StreamInterceptor(grpc_auth.StreamServerInterceptor(authFunc)),
		grpc.UnaryInterceptor(grpc_auth.UnaryServerInterceptor(authFunc)),
	)
	// Attach the Greeter service to the server
	articlespb.RegisterArticleServiceServer(s, sArticle)
	albumspb.RegisterAlbumServiceServer(s, sAlbum)
	// Serve gRPC server
	log.Printf("Serving gRPC on 0.0.0.0:%d", config.AppGrpcPort)
	go func() {
		log.Fatalln(s.Serve(lis))
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
	err = articlespb.RegisterArticleServiceHandler(context.Background(), gwmux, conn)
	if err != nil {
		return fmt.Errorf("Failed to register article gateway: %w", err)
	}

	err = albumspb.RegisterAlbumServiceHandler(context.Background(), gwmux, conn)
	if err != nil {
		return fmt.Errorf("Failed to register albuns gateway: %w", err)
	}

	oa := getOpenAPIHandler()

	gwServer := &http.Server{
		Addr: fmt.Sprintf("0.0.0.0:%d", config.AppHttpPort),
		Handler: http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
			if strings.HasPrefix(r.URL.Path, "/api") {
				gwmux.ServeHTTP(w, r)
				return
			}
			oa.ServeHTTP(w, r)
		}),
	}

	log.Printf("Serving gRPC-Gateway on http://0.0.0.0:%d | http://127.0.0.1:%d\n", config.AppHttpPort, config.AppHttpPort)
	log.Fatalln(gwServer.ListenAndServe())

	return nil
}
