package database2

import (
	"api-go/config"
	"api-go/tutorial"
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
	rows, err := db2.Query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY length(table_name) desc;")
	if err != nil {
		panic(err)
	}
	defer rows.Close()

	var tables []string

	for rows.Next() {
		var t string
		if err := rows.Scan(&t); err != nil {
			panic(err)
		}
		tables = append(tables, t)
	}
	if err = rows.Err(); err != nil {
		panic(err)
	}

	for _, table := range tables {
		db2.Exec("DELETE FROM " + table + " WHERE 1 = 1")
	}
}
