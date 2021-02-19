package albums

import (
	"api-go/api"
	"api-go/db"
	"errors"
	"net/http"

	"github.com/labstack/echo/v4"
	"gorm.io/gorm"
)

// ListAlbums godoc
// @Summary List albums
// @Description Get paginated list of albums
// @Accept  json
// @Produce  json
// @Param page path int true "page" default(1)
// @Param per_page path int true "per_page" default(10)
// @Success 200 {array} model.Album
// @Router /albums [get]
func GetAlbums(c echo.Context) error {
	dbInstance := db.DbManager()

	albums := []Album{}
	page, perPage := api.GetPaginationFromRequest(c)
	dbInstance.Scopes(api.Paginate(page, perPage)).Find(&albums)

	var total int64
	dbInstance.Model(albums).Count(&total)

	return c.JSON(http.StatusOK, api.Paginated{
		Data: albums,
		Meta: api.Meta{Total: total, PerPage: perPage},
	})
}

// ShowAlbums godoc
// @Summary Show a album
// @Description Get an allbum by slug
// @Accept  json
// @Produce  json
// @Param page path string true "Album Slug"
// @Success 200 {object} model.Album
// @Router /albums/{slug} [get]
func GetAlbum(c echo.Context) error {
	db := db.DbManager()
	slug := c.Param("slug")
	album := Album{}
	err := db.Where("slug = ?", slug).Where("private = false").Where("published_at is not null").First(&album).Error

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return echo.NewHTTPError(http.StatusNotFound, "Album not found.")
	}

	return c.JSON(http.StatusOK, album)
}
