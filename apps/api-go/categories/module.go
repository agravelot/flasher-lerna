package categories

import (
	"api-go/db"

	"github.com/labstack/echo/v4"
)

func Setup(e *echo.Echo) {

	db := db.DbManager()

	db.AutoMigrate(&Category{})
}
