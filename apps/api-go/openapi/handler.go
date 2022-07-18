package openapi

import (
	"api-go/third_party"
	"fmt"
	"io/fs"
	"mime"
	"net/http"
)

// getOpenAPIHandler serves an OpenAPI UI.
// Adapted from https://github.com/philips/grpc-gateway-example/blob/a269bcb5931ca92be0ceae6130ac27ae89582ecc/cmd/serve.go#L63
func New() (http.Handler, error) {
	err := mime.AddExtensionType(".svg", "image/svg+xml")
	if err != nil {
		return nil, fmt.Errorf("unable to add extension type for openapi handler: %w", err)
	}
	// Use subdirectory in embedded files
	subFS, err := fs.Sub(third_party.OpenAPI, "OpenAPI")
	if err != nil {
		return nil, fmt.Errorf("couldn't create sub filesystem: %w", err)
	}
	return http.FileServer(http.FS(subFS)), nil
}
