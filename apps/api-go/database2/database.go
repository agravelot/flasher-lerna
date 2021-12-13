package database2

import (
	"api-go/config"
	"api-go/tutorial"
	"context"
	"database/sql"
	"fmt"
	"log"
)

var db *tutorial.Queries
var db2 *sql.DB
var err error

func Init(c config.Configurations) (*tutorial.Queries, error) {

	// newLogger := logger.New(
	// 	log.New(os.Stdout, "\r\n", log.LstdFlags), // io writer
	// 	logger.Config{
	// 		SlowThreshold: time.Second, // Slow SQL threshold
	// 		LogLevel:      logger.Info, // Log level
	// 		Colorful:      true,
	// 	},
	// )

	dsn := fmt.Sprintf("host=%s user=%s password=%s dbname=%s port=%d sslmode=disable", c.DbHost, c.DbUser, c.DbPassword, c.DbName, c.DbPort)

	db2, err = sql.Open("postgres", dsn)
	if err != nil {
		log.Fatalf("Got error when connect database, the error is '%v'", err)
	}

	queries := tutorial.New(db2)

	// db, err = gorm.Open(postgres.Open(dsn), &gorm.Config{Logger: newLogger})
	// if err != nil {
	// 	log.Fatalf("Got error when connect database, the error is '%v'", err)
	// }
	// defer db.Close()
	if err != nil {
		panic("DB Connection Error")
	}

	return queries, nil
}

// DbManager Return Gorm DB instance
func DbManager() *tutorial.Queries {
	return db
}

//TODO
func ClearDB(db *tutorial.Queries) {
	var tables []string

	tables, err := db.PgListAllTables(context.Background())
	if err != nil {
		panic(err)
	}

	for _, table := range tables {
		db2.Exec("DELETE FROM " + table + " WHERE 1 = 1")
	}

	// if err := db.Table("information_schema.tables").Where("table_schema = ?", "public").Order("length(table_name) desc").Pluck("table_name", &tables).Error; err != nil {
	// 	panic(err)
	// }
	// for _, table := range tables {
	// 	db.Exec("DELETE FROM " + table + " WHERE 1 = 1")
	// }
}
