package albums

import (
	"api-go/db"

	"github.com/labstack/echo/v4"
)

func Setup(e *echo.Echo) {
	e.GET("/albums", ListAlbums)
	e.GET("/albums/:slug", ShowAlbum)

	dbi := db.DbManager()

	dbi.AutoMigrate(&Album{})
}
