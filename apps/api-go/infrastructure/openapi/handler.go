package openapi

import (
	"api-go/infrastructure/openapi/third_party"
	"fmt"
	"io/fs"
	"mime"
	"net/http"

	"github.com/grpc-ecosystem/grpc-gateway/v2/runtime"
)

// getOpenAPIHandler serves an OpenAPI UI.
// Adapted from https://github.com/philips/grpc-gateway-example/blob/a269bcb5931ca92be0ceae6130ac27ae89582ecc/cmd/serve.go#L63
func New(path string, mux *runtime.ServeMux) error {
	err := mime.AddExtensionType(".svg", "image/svg+xml")
	if err != nil {
		return fmt.Errorf("unable to add extension type for openapi handler: %w", err)
	}
	// Use subdirectory in embedded files
	subFS, err := fs.Sub(third_party.OpenAPI, "OpenAPI")
	if err != nil {
		return fmt.Errorf("couldn't create sub filesystem: %w", err)
	}
	s := http.FileServer(http.FS(subFS))

	return mux.HandlePath("GET", path, func(w http.ResponseWriter, r *http.Request, pathParams map[string]string) {
		s.ServeHTTP(w, r)
	})
}
