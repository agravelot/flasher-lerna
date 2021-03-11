package albums

import (
	database "api-go/db"

	"github.com/labstack/echo/v4"
)

func Setup(e *echo.Echo) {
	e.GET("/albums", ListAlbums)
	e.GET("/albums/:slug", ShowAlbum)

	dbi := database.DbManager()

	dbi.AutoMigrate(&Album{})
	// db.AutoMigrate(&Category{})
}
