package docs

import (
	"github.com/labstack/echo/v4"

	echoSwagger "github.com/swaggo/echo-swagger"
)

func Setup(e *echo.Echo) {
	e.GET("/swagger/*", echoSwagger.WrapHandler)
}
