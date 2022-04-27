package main

import (
	"context"
	"io/fs"
	"io/ioutil"
	"log"
	"mime"
	"net"
	"net/http"
	"os"
	"strings"

	"github.com/grpc-ecosystem/grpc-gateway/v2/runtime"
	"google.golang.org/grpc"
	"google.golang.org/grpc/credentials/insecure"
	"google.golang.org/grpc/grpclog"

	"api-go/article"
	"api-go/config"
	"api-go/database"
	articlespb "api-go/gen/go/proto/articles/v1"
	"api-go/third_party"
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

func main() {

	config := config.LoadDotEnv("")

	// var logger log.Logger
	// {
	// 	logger = log.NewLogfmtLogger(os.Stderr)
	// 	logger = log.With(logger, "ts", log.DefaultTimestampUTC)
	// 	logger = log.With(logger, "caller", log.DefaultCaller)
	// }

	orm, _ := database.Init(config)

	// var sAlbum album.Service
	// {
	// 	sAlbum = album.NewService(orm)
	// 	// sAlbum = album.LoggingMiddleware(logger)(sAlbum)
	// }

	var sArticle article.Service
	{
		sArticle = article.NewService(orm)
		// sArticle = article.LoggingMiddleware(logger)(sArticle)
	}

	// Adds gRPC internal logs. This is quite verbose, so adjust as desired!
	l := grpclog.NewLoggerV2(os.Stdout, ioutil.Discard, ioutil.Discard)
	grpclog.SetLoggerV2(l)

	// Create a listener on TCP port
	lis, err := net.Listen("tcp", ":8080")
	if err != nil {
		log.Fatalln("Failed to listen:", err)
	}

	// Create a gRPC server object
	s := grpc.NewServer()
	// Attach the Greeter service to the server
	articlespb.RegisterArticleServiceServer(s, sArticle)
	// Serve gRPC server
	log.Println("Serving gRPC on 0.0.0.0:8080")
	go func() {
		log.Fatalln(s.Serve(lis))
	}()

	// Create a client connection to the gRPC server we just started
	// This is where the gRPC-Gateway proxies the requests
	conn, err := grpc.DialContext(
		context.Background(),
		"0.0.0.0:8080",
		grpc.WithBlock(),
		grpc.WithTransportCredentials(insecure.NewCredentials()),
	)
	if err != nil {
		log.Fatalln("Failed to dial server:", err)
	}

	gwmux := runtime.NewServeMux()
	// Register Greeter
	err = articlespb.RegisterArticleServiceHandler(context.Background(), gwmux, conn)
	if err != nil {
		log.Fatalln("Failed to register article gateway:", err)
	}

	// gwServer := &http.Server{
	// 	Addr:    ":8090",
	// 	Handler: gwmux,
	// }
	oa := getOpenAPIHandler()

	gwServer := &http.Server{
		Addr: ":8090",
		Handler: http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
			if strings.HasPrefix(r.URL.Path, "/api") {
				gwmux.ServeHTTP(w, r)
				return
			}
			oa.ServeHTTP(w, r)
		}),
	}

	log.Println("Serving gRPC-Gateway on http://0.0.0.0:8090")
	log.Fatalln(gwServer.ListenAndServe())
}
