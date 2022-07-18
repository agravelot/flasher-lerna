package app

import (
	"context"
	"fmt"
	"log"
	"net"
	"net/http"
	"os"
	"strings"

	"github.com/grpc-ecosystem/grpc-gateway/v2/runtime"
	"google.golang.org/grpc"
	"google.golang.org/grpc/credentials/insecure"
	"google.golang.org/grpc/grpclog"

	"api-go/album"
	"api-go/article"
	"api-go/auth"
	"api-go/config"
	"api-go/database"
	albumspb "api-go/gen/go/proto/albums/v2"
	articlespb "api-go/gen/go/proto/articles/v2"
	"api-go/openapi"

	grpc_auth "github.com/grpc-ecosystem/go-grpc-middleware/auth"
)

// Run creates objects via constructors.
func Run(config *config.Configurations) error {

	// var logger log.Logger
	// {
	// 	logger = log.NewLogfmtLogger(os.Stderr)
	// 	logger = log.With(logger, "ts", log.DefaultTimestampUTC)
	// 	logger = log.With(logger, "caller", log.DefaultCaller)
	// }

	orm, err := database.New(config)
	if err != nil {
		return fmt.Errorf("could not connect to database: %w", err)
	}

	db, err := orm.DB.DB()
	if err != nil {
		return fmt.Errorf("could not get DB: %w", err)
	}
	defer db.Close()

	var sAlbum albumspb.AlbumServiceServer
	{
		sAlbum = album.NewService(orm.DB)
		// sAlbum = album.LoggingMiddleware(logger)(sAlbum)
	}

	var sArticle articlespb.ArticleServiceServer
	{
		sArticle = article.NewService(orm.DB)
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
		grpc.StreamInterceptor(grpc_auth.StreamServerInterceptor(auth.AuthFunc)),
		grpc.UnaryInterceptor(grpc_auth.UnaryServerInterceptor(auth.AuthFunc)),
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
		return fmt.Errorf("failed to register article gateway: %w", err)
	}

	err = albumspb.RegisterAlbumServiceHandler(context.Background(), gwmux, conn)
	if err != nil {
		return fmt.Errorf("failed to register albuns gateway: %w", err)
	}

	oa, err := openapi.New()
	if err != nil {
		return fmt.Errorf("unable to init openapi for application: %w", err)
	}

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
