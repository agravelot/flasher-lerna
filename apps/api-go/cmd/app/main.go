package main

import (
	"api-go/config"
	"api-go/internal/app"
)

func main() {

	config := config.LoadDotEnv("")

	app.Run(config)
}
