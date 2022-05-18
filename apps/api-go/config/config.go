package config

import (
	"fmt"
	"log"

	"github.com/joho/godotenv"
	"github.com/kelseyhightower/envconfig"
)

type Configurations struct {
	// Debug      bool   `envconfig:"DEBUG"`
	Port       int    `envconfig:"PORT" required:"true"`
	DbHost     string `envconfig:"DB_HOST" required:"true"`
	DbUser     string `envconfig:"DB_USER" required:"true"`
	DbPassword string `envconfig:"DB_PASSWORD" required:"true"`
	DbName     string `envconfig:"DB_NAME" required:"true"`
	DbPort     int    `envconfig:"DB_PORT" required:"true"`
	DbSslMode  string `envconfig:"DB_SSL_MODE" required:"true"`
}

func LoadDotEnv(path string) *Configurations {

	if err := godotenv.Load(path + ".env"); err != nil {
		log.Println(err)
	}

	var c Configurations
	err := envconfig.Process("", &c)

	if err != nil {
		panic(fmt.Errorf("failed to process config: %w", err))
	}

	return &c
}
