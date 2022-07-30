package main

import (
	"fmt"
	"log"

	"api-go/config"
	"api-go/internal/app"
)

func main() {
	config, err := config.FromDotEnv(".env")
	if err != nil {
		log.Fatal(fmt.Errorf("unable to load config: %w", err))
	}

	err = app.Run(config)
	if err != nil {
		log.Fatal(fmt.Errorf("unable to load config: %w", err))
	}
}
