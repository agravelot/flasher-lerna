package albums

import (
	"api-go/api"
	"errors"
	"net/http"

	"github.com/labstack/echo/v4"
	"gorm.io/gorm"
)

// ListAlbums Gall all public albums
// @Summary List albums
// @Description Get paginated list of albums
// @Accept  json
// @Produce  json
// @Param page path int true "page" default(1)
// @Param per_page path int true "per_page" default(10)
// @Success 200 {array} model.Album
// @Router /albums [get]
func ListAlbums(c echo.Context) error {
	page, perPage := api.GetPaginationFromRequest(c)

	albums := GetAlbumsPaginated(page, perPage)

	return c.JSON(http.StatusOK, albums)
}

// ShowAlbum godoc
// @Summary Show a album
// @Description Get an allbum by slug
// @Accept  json
// @Produce  json
// @Param page path string true "Album Slug"
// @Success 200 {object} model.Album
// @Router /albums/{slug} [get]
func ShowAlbum(c echo.Context) error {
	slug := c.Param("slug")
	album, err := GetAlbumBySlug(slug)

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return echo.NewHTTPError(http.StatusNotFound, "Album not found.")
	}

	return c.JSON(http.StatusOK, album)
}
