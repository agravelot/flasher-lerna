package postgres

import (
	"database/sql"
	"fmt"
	"log"
	"os"
	"time"

	"gorm.io/driver/postgres"
	"gorm.io/gorm"
	"gorm.io/gorm/logger"
)

type Postgres struct {
	// TODO Make it private
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
	debug := false

	logLevel := logger.Silent
	if debug {
		logLevel = logger.Info
	}

	newLogger := logger.New(
		log.New(os.Stdout, "\r\n", log.LstdFlags), // io writer
		logger.Config{
			SlowThreshold: time.Second, // Slow SQL threshold
			LogLevel:      logLevel,    // Log level
			Colorful:      true,
		},
	)

	config := &gorm.Config{
		Logger: newLogger,
	}

	sqlDB, err := sql.Open("pgx", uri)
	if err != nil {
		return Postgres{}, fmt.Errorf("unable to connect to the database: %w", err)
	}

	db, err := gorm.Open(postgres.New(postgres.Config{
		Conn: sqlDB,
	}), config)
	if err != nil {
		return Postgres{}, fmt.Errorf("unable to connect to the database: %w", err)
	}

	return Postgres{db}, nil
}
