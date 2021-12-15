package database2

import (
	"api-go/config"
	"api-go/tutorial"
	"context"
	"database/sql"
	"fmt"
	"log"
	"time"

	"github.com/lib/pq"
	"github.com/qustavo/sqlhooks/v2"
)

var db *tutorial.Queries
var db2 *sql.DB
var err error

// Hooks satisfies the sqlhook.Hooks interface
type Hooks struct{}

// Before hook will print the query with it's args and return the context with the timestamp
func (h *Hooks) Before(ctx context.Context, query string, args ...interface{}) (context.Context, error) {
	// fmt.Printf("> %s %q", query, args)
	return context.WithValue(ctx, "begin", time.Now()), nil
}

// After hook will get the timestamp registered on the Before hook and print the elapsed time
func (h *Hooks) After(ctx context.Context, query string, args ...interface{}) (context.Context, error) {
	// begin := ctx.Value("begin").(time.Time)
	// fmt.Printf(". took: %s\n", time.Since(begin))
	return ctx, nil
}

func Init(c config.Configurations) (*tutorial.Queries, error) {

	// newLogger := logger.New(
	// 	log.New(os.Stdout, "\r\n", log.LstdFlags), // io writer
	// 	logger.Config{
	// 		SlowThreshold: time.Second, // Slow SQL threshold
	// 		LogLevel:      logger.Info, // Log level
	// 		Colorful:      true,
	// 	},
	// )

	sql.Register("postgresWithHooks", sqlhooks.Wrap(&pq.Driver{}, &Hooks{}))

	dsn := fmt.Sprintf("host=%s user=%s password=%s dbname=%s port=%d sslmode=disable", c.DbHost, c.DbUser, c.DbPassword, c.DbName, c.DbPort)

	db2, err = sql.Open("postgresWithHooks", dsn)
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
		// println("Deleting table: " + table)
		_, err := db2.Exec("DELETE FROM " + table + " WHERE 1 = 1")
		if err != nil {
			panic(fmt.Errorf("error deleting table %s: %w", table, err))
		}
	}
}
