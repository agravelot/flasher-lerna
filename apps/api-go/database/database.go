package database

import (
	"api-go/config"
	"fmt"
	"log"
	"os"
	"time"

	"gorm.io/driver/postgres"
	"gorm.io/gorm"
	"gorm.io/gorm/logger"
)

type Database struct {
	DB *gorm.DB
}

func New(c *config.Configurations) (Database, error) {

	newLogger := logger.New(
		log.New(os.Stdout, "\r\n", log.LstdFlags), // io writer
		logger.Config{
			SlowThreshold: time.Second, // Slow SQL threshold
			LogLevel:      logger.Info, // Log level
			Colorful:      true,
		},
	)

	dsn := fmt.Sprintf("host=%s user=%s password=%s dbname=%s port=%d sslmode=%s", c.DbHost, c.DbUser, c.DbPassword, c.DbName, c.DbPort, c.DbSslMode)

	config := &gorm.Config{}
	if true {
		config.Logger = newLogger
	}

	db, err := gorm.Open(postgres.Open(dsn), config)
	if err != nil {
		return Database{}, fmt.Errorf("unable to connect to the database: %w", err)
	}

	return Database{
		DB: db,
	}, nil
}
