package database2

import (
	"api-go/config"
	"api-go/tutorial"
	"context"
	"fmt"
	"os"
	"time"

	"github.com/jackc/pgx/v4/pgxpool"
)

var db *tutorial.Queries
var conn *pgxpool.Pool
var err error

// Hooks satisfies the sqlhook.Hooks interface
type Hooks struct{}

// Before hook will print the query with it's args and return the context with the timestamp
func (h *Hooks) Before(ctx context.Context, query string, args ...interface{}) (context.Context, error) {
	// log.Printf("> %s %q", query, args)
	return context.WithValue(ctx, "begin", time.Now()), nil
}

// After hook will get the timestamp registered on the Before hook and print the elapsed time
func (h *Hooks) After(ctx context.Context, query string, args ...interface{}) (context.Context, error) {
	// begin := ctx.Value("begin").(time.Time)
	// log.Printf(". took: %s\n", time.Since(begin))
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

	// sql.Register("postgresWithHooks", sqlhooks.Wrap(&pq.Driver{}, &Hooks{}))

	dsn := fmt.Sprintf("host=%s user=%s password=%s dbname=%s port=%d sslmode=disable", c.DbHost, c.DbUser, c.DbPassword, c.DbName, c.DbPort)

	// db2, err = sql.Open("postgresWithHooks", dsn)
	// if err != nil {
	// 	log.Fatalf("Got error when connect database, the error is '%v'", err)
	// }

	conn, err = pgxpool.Connect(context.Background(), dsn)
	if err != nil {
		fmt.Fprintf(os.Stderr, "Unable to connect to database: %v\n", err)
		os.Exit(1)
	}

	// TODO Add defer close somewhere
	// defer conn.Close(context.Background())

	queries := tutorial.New(conn)

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

func ClearDB(db *tutorial.Queries) {
	rows, err := conn.Query(context.Background(), "SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY length(table_name) desc;")

	var tables []string
	for rows.Next() {
		var table string
		err = rows.Scan(&table)
		if err != nil {
			panic(fmt.Errorf("error reading rows on querying tables listing : %w", err))
		}
		tables = append(tables, table)
	}

	if err != nil {
		panic(fmt.Errorf("error querying tables listing : %w", err))
	}

	for _, table := range tables {
		// println("Deleting table: " + table)
		_, err := conn.Exec(context.Background(), "DELETE FROM "+table+" WHERE 1 = 1")
		if err != nil {
			panic(fmt.Errorf("error deleting table %s: %w", table, err))
		}
	}
}
