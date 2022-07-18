package main

import (
	"api-go/config"
	"api-go/internal/app"
	"fmt"
	"log"
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
