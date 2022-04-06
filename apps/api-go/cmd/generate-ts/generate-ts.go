package main

import (
	album "api-go/albums"
	"api-go/article"
	"time"

	"github.com/tkrajina/typescriptify-golang-structs/typescriptify"
)

func main() {
	converter := typescriptify.New()
	converter.CreateInterface = true
	converter.BackupDir = ""
	converter.ManageType(time.Time{}, typescriptify.TypeOptions{TSType: "string", TSTransform: "__VALUE__"})
	converter.Add(article.ArticleRequest{})
	converter.Add(article.ArticleUpdateRequest{})
	converter.Add(article.ArticleResponse{})
	converter.Add(album.AlbumRequest{})
	converter.Add(album.AlbumResponse{})
	converter.Add(album.AlbumUpdateRequest{})
	err := converter.ConvertToFile("./models.ts")
	if err != nil {
		panic(err.Error())
	}
}
