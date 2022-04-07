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

	articlespb "api-go/gen/go/proto/articles/v1"
	"api-go/third_party"
)

type server struct {
	articlespb.UnimplementedArticleServiceServer
}

func NewServer() *server {
	return &server{}
}

func (s *server) Index(ctx context.Context, in *articlespb.IndexRequest) (*articlespb.IndexResponse, error) {
	return &articlespb.IndexResponse{Articles: []*articlespb.ArticleReponse{
		{Id: 1, Name: "Article 1", Content: "Content 1"},
	}}, nil
}

// GetBySlug(context.Context, *GetBySlugRequest) (*GetBySlugResponse, error)
// 	Create(context.Context, *CreateRequest) (*CreateResponse, error)
// 	Delete(context.Context, *DeleteRequest) (*DeleteResponse, error)

func (s *server) GetBySlug(ctx context.Context, in *articlespb.GetBySlugRequest) (*articlespb.GetBySlugResponse, error) {
	return &articlespb.GetBySlugResponse{Article: &articlespb.ArticleReponse{Name: "asdsad"}}, nil
}

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
	articlespb.RegisterArticleServiceServer(s, &server{})
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
