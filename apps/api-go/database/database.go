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

func Init(c config.Configurations) (*gorm.DB, error) {

	newLogger := logger.New(
		log.New(os.Stdout, "\r\n", log.LstdFlags), // io writer
		logger.Config{
			SlowThreshold: time.Second, // Slow SQL threshold
			LogLevel:      logger.Info, // Log level
			Colorful:      true,
		},
	)

	dsn := fmt.Sprintf("host=%s user=%s password=%s dbname=%s port=%d sslmode=%s", c.DbHost, c.DbUser, c.DbPassword, c.DbName, c.DbPort, c.DbSslMode)

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

func ClearDB(db *gorm.DB) {
	var tables []string
	if err := db.Table("information_schema.tables").Where("table_schema = ?", "public").Order("length(table_name) desc").Pluck("table_name", &tables).Error; err != nil {
		panic(err)
	}
	for _, table := range tables {
		db.Exec("DELETE FROM " + table + " WHERE 1 = 1")
	}
}