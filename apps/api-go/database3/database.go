package database3

import (
	"api-go/config"
	"api-go/ent"
	"database/sql"
	"fmt"
	"log"

	"entgo.io/ent/dialect"
	entsql "entgo.io/ent/dialect/sql"
	_ "github.com/jackc/pgx/v4/stdlib"
)

var orm *ent.Client
var conn *sql.DB

// Open new connection
func Open(url string) *ent.Client {

	db, err := sql.Open("pgx", url)
	if err != nil {
		log.Fatal(err)
	}
	conn = db

	// Create an ent.Driver from `db`.
	drv := entsql.OpenDB(dialect.Postgres, db)
	return ent.NewClient(ent.Driver(drv), ent.Debug())
}

func Init(c config.Configurations) (*ent.Client, error) {

	url := fmt.Sprintf("postgresql://%s:%s@%s:%d/%s", c.DbUser, c.DbPassword, c.DbHost, c.DbPort, c.DbName)

	client := Open(url)

	return client, nil
}

// DbManager Return Gorm DB instance
func DbManager() *ent.Client {
	return orm
}

func ClearDB() {
	rows, err := conn.Query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY length(table_name) desc;")

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
		_, err := conn.Exec("DELETE FROM " + table + " WHERE 1 = 1")
		if err != nil {
			panic(fmt.Errorf("error deleting table %s: %w", table, err))
		}
	}
}
