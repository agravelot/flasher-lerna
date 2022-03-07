

# gen --sqltype=postgres --database=flasher --connstr "host=localhost user=flasher password=flasher dbname=flasher port=5432 sslmode=disable" --gorm --module=example.com/examplegen --table=albums 

gentool -db postgres -dsn "host=localhost user=flasher password=flasher dbname=flasher port=5432 sslmode=disable" -outPath=gormQuery/ -fieldNullable -fieldWithTypeTag -fieldWithIndexTag