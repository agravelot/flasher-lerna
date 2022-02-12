# Flasher api

## Init database

```bash
goose postgres "user=flasher password=flasher dbname=flasher sslmode=disable" up
```

## Generate ent
```bash
go run ariga.io/entimport/cmd/entimport -dsn "postgres://flasher:flasher@localhost:5432/flasher?sslmode=disable" -tables albums,medias,album_category,categories,album_cosplayer,cosplayers,settings,social_medias
```

