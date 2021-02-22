package db

import (
	"log"
	"os"
	"time"

	"gorm.io/driver/postgres"
	"gorm.io/gorm"
	"gorm.io/gorm/logger"
)

var db *gorm.DB
var err error

func Init() (*gorm.DB, error) {

	newLogger := logger.New(
		log.New(os.Stdout, "\r\n", log.LstdFlags), // io writer
		logger.Config{
			SlowThreshold: time.Second, // Slow SQL threshold
			LogLevel:      logger.Info, // Log level
			Colorful:      true,
		},
	)

	dsn := "host=localhost user=flasher password=flasher dbname=flasher port=5432 sslmode=disable"
	db, err = gorm.Open(postgres.Open(dsn), &gorm.Config{Logger: newLogger})
	if err != nil {
		log.Fatalf("Got error when connect database, the error is '%v'", err)
	}
	// defer db.Close()
	if err != nil {
		panic("DB Connection Error")
	}

	return db, nil
}

// DbManager Return Gorm DB instance
func DbManager() *gorm.DB {
	return db
}