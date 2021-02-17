package route

import (
	"api-go/api"
	"net/http"

	"github.com/labstack/echo/v4"
)

func Init(e *echo.Echo) *echo.Echo {

	e.GET("/", func(c echo.Context) error {
		return c.String(http.StatusOK, "Hello, World!")
	})

	v1 := e.Group("/api/v1")
	{
		// v1.POST("/testimonials", api.PostTestimonial())
		v1.GET("/albums", api.GetAlbums)
		v1.GET("/albums/:slug", api.GetAlbum)

		v1.GET("/testimonials", api.GetTestimonials)
		v1.GET("/testimonials/:id", api.GetTestimonial)
	}

	return e
}
