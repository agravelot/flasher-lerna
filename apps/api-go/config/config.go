package config

import (
	"fmt"

	"github.com/joho/godotenv"
	"github.com/kelseyhightower/envconfig"
)

type Configurations struct {
	Debug      bool   `envconfig:"DEBUG"`
	Port       int    `envconfig:"PORT"`
	DbHost     string `envconfig:"DB_HOST" required:"true"`
	DbUser     string `envconfig:"DB_USER" required:"true"`
	DbPassword string `envconfig:"DB_PASSWORD" required:"true"`
	DbName     string `envconfig:"DB_NAME" required:"true"`
	DbPort     int    `envconfig:"DB_PORT" required:"true"`
}

func LoadDotEnv() Configurations {

	if err := godotenv.Load(".env"); err != nil {
		fmt.Println(err)
	}

	var c Configurations
	err := envconfig.Process("", &c)

	if err != nil {
		panic(err)
	}

	return c
}
