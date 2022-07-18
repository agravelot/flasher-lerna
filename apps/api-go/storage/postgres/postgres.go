package postgres

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

type Postgres struct {
	DB *gorm.DB
}

func (d Postgres) Close() error {
	db, err := d.DB.DB()
	if err != nil {
		return err
	}
	return db.Close()
}

func New(c *config.Config) (Postgres, error) {

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
		return Postgres{}, fmt.Errorf("unable to connect to the database: %w", err)
	}

	return Postgres{
		DB: db,
	}, nil
}
