package app

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
	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/credentials/insecure"
	"google.golang.org/grpc/grpclog"
	"google.golang.org/grpc/status"

	album "api-go/albums"
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
		return nil, err
	}

	parsedToken, err := auth.ParseToken(token)
	if err != nil {
		return nil, status.Errorf(codes.Unauthenticated, "invalid auth token: %v", err)
	}

	grpc_ctxtags.Extract(ctx).Set("user", parsedToken)

	// WARNING: in production define your own type to avoid context collisions
	newCtx := context.WithValue(ctx, "user", parsedToken)

	return newCtx, nil
}

// Run creates objects via constructors.
func Run(config *config.Configurations) {

	// var logger log.Logger
	// {
	// 	logger = log.NewLogfmtLogger(os.Stderr)
	// 	logger = log.With(logger, "ts", log.DefaultTimestampUTC)
	// 	logger = log.With(logger, "caller", log.DefaultCaller)
	// }

	orm, err := database.Init(config)
	if err != nil {
		log.Fatalln("could not connect to database: %w", err)
	}

	db, err := orm.DB()
	if err != nil {
		log.Fatalln("could not get DB: %w", err)
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
	l := grpclog.NewLoggerV2(os.Stdout, ioutil.Discard, ioutil.Discard)
	grpclog.SetLoggerV2(l)

	// Create a listener on TCP port
	lis, err := net.Listen("tcp", ":8080")
	if err != nil {
		log.Fatalln("Failed to listen:", err)
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
		Addr: "0.0.0.0:8090",
		Handler: http.HandlerFunc(func(w http.ResponseWriter, r *http.Request) {
			if strings.HasPrefix(r.URL.Path, "/api") {
				gwmux.ServeHTTP(w, r)
				return
			}
			oa.ServeHTTP(w, r)
		}),
	}

	log.Println("Serving gRPC-Gateway on http://127.0.0.1:8090")
	log.Fatalln(gwServer.ListenAndServe())
}
