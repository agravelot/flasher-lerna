//go:generate go run github.com/bufbuild/buf/cmd/buf@v1.25.0 generate
//go:generate cp gen/openapiv2/all.swagger.json pkg/openapi/third_party/OpenAPI/swagger.json
//go:generate go run github.com/go-swagger/go-swagger/cmd/swagger@master generate markdown -f gen/openapiv2/all.swagger.json --output API.md
//  // go:generate go run cmd/generate-orm/generate.go

package tools
