package api

import (
	"api-go/db"
	"api-go/model"
	"net/http"

	"github.com/labstack/echo/v4"
)

func GetAlbums(c echo.Context) error {
	dbInstance := db.DbManager()

	albums := []model.Album{}
	page, perPage := GetPaginationFromRequest(c)
	dbInstance.Scopes(Paginate(page, perPage)).Find(&albums)

	var total int64
	dbInstance.Model(albums).Count(&total)

	return c.JSON(http.StatusOK, Paginated{
		Data: albums,
		Meta: Meta{Total: total, PerPage: perPage},
	})
}

func GetAlbum(c echo.Context) error {
	db := db.DbManager()
	slug := c.Param("slug")
	album := model.Album{Slug: slug}
	db.First(&album)
	return c.JSON(http.StatusOK, album)
}
