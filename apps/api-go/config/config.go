package config

import (
	"fmt"
	"log"

	"github.com/joho/godotenv"
	"github.com/kelseyhightower/envconfig"
)

type Config struct {
	// Debug      bool   `envconfig:"DEBUG"`
	AppHttpPort int    `envconfig:"APP_HTTP_PORT" required:"true"`
	AppGrpcPort int    `envconfig:"APP_GRPC_PORT" required:"true"`
	DbHost      string `envconfig:"DB_HOST" required:"true"`
	DbUser      string `envconfig:"DB_USER" required:"true"`
	DbPassword  string `envconfig:"DB_PASSWORD" required:"true"`
	DbName      string `envconfig:"DB_NAME" required:"true"`
	DbPort      int    `envconfig:"DB_PORT" required:"true"`
	DbSslMode   string `envconfig:"DB_SSL_MODE" required:"true"`
}

func FromDotEnv(path string) (*Config, error) {
	err := godotenv.Load(path)
	if err != nil {
		log.Println(err)
	}

	return FromEnv()
}

func FromEnv() (*Config, error) {
	var cfg Config
	err := envconfig.Process("", &cfg)
	if err != nil {
		return &cfg, fmt.Errorf("failed to process config: %w", err)
	}

	return &cfg, nil
}
