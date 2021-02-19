package albums

import (
	"api-go/db"

	"github.com/labstack/echo/v4"
)

func Setup(e *echo.Group) {
	e.GET("/albums", GetAlbums)
	e.GET("/albums/:slug", GetAlbum)

	dbi := db.DbManager()

	dbi.AutoMigrate(&Album{})
}
