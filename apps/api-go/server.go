package main

import (
	"api-go/db"
	"api-go/route"
	"fmt"

	"github.com/labstack/echo/v4"
	"github.com/labstack/echo/v4/middleware"
)

func main() {
	e := echo.New()

	db.Init()
	route.Init(e)

	// e.Use(middleware.Recover())
	e.Use(middleware.Logger())

	fmt.Println("http://localhost:1323")
	e.Logger.Fatal(e.Start(":1323"))
}
