package main

import (
	database "api-go/db"
	"api-go/docs"
	"api-go/route"
	"fmt"

	"github.com/labstack/echo/v4"
	"github.com/labstack/echo/v4/middleware"
)

// @title Swagger Example API
// @version 1.0
// @description This is a sample server celler server.

// @host localhost:1323
// @BasePath /api/v1
func main() {
	e := echo.New()

	docs.Setup(e)

	database.Init()
	route.Init(e)

	// e.Use(middleware.Recover())
	e.Use(middleware.Logger())

	fmt.Println("http://localhost:1323")
	e.Logger.Fatal(e.Start(":1323"))
}
