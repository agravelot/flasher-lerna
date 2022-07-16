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

var db *gorm.DB
var err error

func Init(c *config.Configurations) (*gorm.DB, error) {

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

	db, err = gorm.Open(postgres.Open(dsn), config)
	if err != nil {
		return db, fmt.Errorf("unable to connect to the database: %w", err)
	}

	return db, nil
}

// DbManager Return Gorm DB instance
func DbManager() *gorm.DB {
	return db
}
