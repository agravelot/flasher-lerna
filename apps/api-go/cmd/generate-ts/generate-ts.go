package main

import (
	"time"

	"github.com/tkrajina/typescriptify-golang-structs/typescriptify"
)

// articlesgrpc "api-go/gen/go/proto/articles/v2"
// album "api-go/albums"

func main() {
	converter := typescriptify.New()
	converter.CreateInterface = true
	converter.BackupDir = ""
	converter.ManageType(time.Time{}, typescriptify.TypeOptions{TSType: "string", TSTransform: "__VALUE__"})
	// converter.Add(articlesgrpc.IndexRequest{})
	// converter.Add(articlesgrpc.IndexResponse{})
	// converter.Add(articlesgrpc.GetBySlugRequest{})
	// converter.Add(articlesgrpc.GetBySlugResponse{})
	// converter.Add(articlesgrpc.CreateRequest{})
	// converter.Add(articlesgrpc.CreateResponse{})
	// converter.Add(articlesgrpc.UpdateRequest{})
	// converter.Add(articlesgrpc.UpdateResponse{})
	// converter.Add(articlesgrpc.DeleteRequest{})
	// converter.Add(articlesgrpc.DeleteResponse{})
	// converter.Add(album.AlbumRequest{})
	// converter.Add(album.AlbumResponse{})
	// converter.Add(album.AlbumUpdateRequest{})
	err := converter.ConvertToFile("./models.ts")
	if err != nil {
		panic(err.Error())
	}
}
