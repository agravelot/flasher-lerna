package postgres

import (
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

func (d Postgres) Begin() Postgres {
	d.DB = d.DB.Begin()
	return d
}

func (d Postgres) Rollback() Postgres {
	d.DB = d.DB.Rollback()
	return d
}

func New(uri string) (Postgres, error) {

	newLogger := logger.New(
		log.New(os.Stdout, "\r\n", log.LstdFlags), // io writer
		logger.Config{
			SlowThreshold: time.Second, // Slow SQL threshold
			LogLevel:      logger.Info, // Log level
			Colorful:      true,
		},
	)

	config := &gorm.Config{}
	if true {
		config.Logger = newLogger
	}

	db, err := gorm.Open(postgres.Open(uri), config)
	if err != nil {
		return Postgres{}, fmt.Errorf("unable to connect to the database: %w", err)
	}

	return Postgres{db}, nil
}
