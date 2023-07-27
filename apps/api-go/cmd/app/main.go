package main

import (
	"fmt"
	"log"

	"api-go/config"
	"api-go/internal/app"
)

func main() {
	cfg, err := config.FromDotEnv(".env")
	if err != nil {
		log.Fatal(fmt.Errorf("unable to load config: %w", err))
	}

	err = app.Run(cfg)
	if err != nil {
		log.Fatal(fmt.Errorf("unable to start server: %w", err))
	}
}
