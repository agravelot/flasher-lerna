package config

import (
	"fmt"
	"log"

	"github.com/joho/godotenv"
	"github.com/kelseyhightower/envconfig"
)

type Config struct {
	// Debug       bool `envconfig:"DEBUG"`
	AppHttpPort int `envconfig:"APP_HTTP_PORT" required:"true"`
	AppGrpcPort int `envconfig:"APP_GRPC_PORT" required:"true"`
	Database    struct {
		Engine string `envconfig:"DB_ENGINE" default:"postgres"`
		URL    string `envconfig:"DB_URL" required:"true"`
		// Host     string `envconfig:"DB_HOST" required:"false"`
		// User     string `envconfig:"DB_USER" required:"false"`
		// Password string `envconfig:"DB_PASSWORD" required:"false"`
		// Name     string `envconfig:"DB_NAME" required:"false"`
		// Port     int    `envconfig:"DB_PORT" required:"false"`
		// SslMode  string `envconfig:"DB_SSL_MODE" required:"false"`
	}
	Keycloak struct {
		Url            string `envconfig:"KEYCLOAK_URL"`
		Realm          string `envconfig:"KEYCLOAK_REALM"`
		ServiceAccount struct {
			User     string `envconfig:"KEYCLOAK_SERVICE_ACCOUNT_USER" required:"false"`
			Password string `envconfig:"KEYCLOAK_SERVICE_ACCOUNT_PASSWORD" required:"false"`
		}
	}
}

func FromDotEnv(path string) (*Config, error) {
	if err := godotenv.Load(path); err != nil {
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
