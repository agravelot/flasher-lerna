package api

import (
	"api-go/db"
	"api-go/model"
	"net/http"

	"github.com/labstack/echo/v4"
)

// ListAlbums godoc
// @Summary List albums
// @Description Get paginated list of albums
// @ID get-string-by-int
// @Accept  json
// @Produce  json
// @Param page path int true "page" default(1)
// @Param per_page path int true "per_page" default(10)
// @Success 200 {array} model.Album
// @Router /albums [get]
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
