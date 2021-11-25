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

	// albums.Setup(e)
	// categories.Setup(e)

	e.GET("/testimonials", api.GetTestimonials)
	e.GET("/testimonials/:id", api.GetTestimonial)

	return e
}
