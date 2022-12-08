package app

import (
	"api-go/infrastructure/auth"
	"api-go/infrastructure/openapi"
	"context"
	"fmt"
	"log"
	"net"
	"net/http"
	"os"
	"os/signal"
	"syscall"

	"github.com/grpc-ecosystem/grpc-gateway/v2/runtime"
	"google.golang.org/grpc"
	"google.golang.org/grpc/credentials/insecure"
	"google.golang.org/grpc/grpclog"

	"api-go/config"
	"api-go/domain/album"
	"api-go/domain/article"
	albumspb "api-go/gen/go/proto/albums/v2"
	articlespb "api-go/gen/go/proto/articles/v2"
	"api-go/infrastructure/storage/postgres"
	grpc_auth "github.com/grpc-ecosystem/go-grpc-middleware/auth"
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
	defer orm.Close()

	albumRepo, err := postgres.NewAlbumRepository(&orm)
	if err != nil {
		return err
	}

	sAlbum, err := album.NewService(albumRepo)
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
		grpc.StreamInterceptor(grpc_auth.StreamServerInterceptor(auth.Interceptor)),
		grpc.UnaryInterceptor(grpc_auth.UnaryServerInterceptor(auth.Interceptor)),
	)
	// Attach the services to the server
	articlespb.RegisterArticleServiceServer(grpcServer, sArticle)
	albumspb.RegisterAlbumServiceServer(grpcServer, sAlbum)
	// Serve gRPC server
	go func() {
		log.Printf("Serving gRPC on 0.0.0.0:%d", config.AppGrpcPort)
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
	err = articlespb.RegisterArticleServiceHandler(context.Background(), gwmux, conn)
	if err != nil {
		return fmt.Errorf("failed to register article gateway: %w", err)
	}

	err = albumspb.RegisterAlbumServiceHandler(context.Background(), gwmux, conn)
	if err != nil {
		return fmt.Errorf("failed to register albuns gateway: %w", err)
	}

	err = openapi.New("/", gwmux)
	if err != nil {
		return fmt.Errorf("unable to init openapi for application: %w", err)
	}

	gwServer := &http.Server{
		Addr:    fmt.Sprintf("0.0.0.0:%d", config.AppHttpPort),
		Handler: gwmux,
	}

	go func() {
		sigquit := make(chan os.Signal, 1)
		signal.Notify(sigquit, os.Interrupt, syscall.SIGTERM)

		sig := <-sigquit
		log.Printf("caught sig: %+v", sig)
		log.Println("gracefully shuting down servers...")

		err := gwServer.Shutdown(context.Background())
		if err != nil {
			log.Printf("unable to shut down http sever: %v", err)
		}
		log.Println("http server stopped")

		grpcServer.GracefulStop()
		log.Println("grpc server stopped")

		err = lis.Close()
		if err != nil {
			log.Printf("unable to close socket: %v", err)
		}
	}()

	log.Printf("Serving gRPC-Gateway on http://0.0.0.0:%d | http://127.0.0.1:%d\n", config.AppHttpPort, config.AppHttpPort)
	err = gwServer.ListenAndServe()
	if err != nil {
		log.Fatalln(gwServer.ListenAndServe())
	}

	return nil
}
