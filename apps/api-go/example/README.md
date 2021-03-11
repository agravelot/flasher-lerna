[comment]: <> (This is a generated file please edit source in ./templates)
[comment]: <> (All modification will be lost, you have been warned)
[comment]: <> ()
### Sample CRUD API for the postgres database host=127.0.0.1 port=5432 user=flasher dbname=flasher password=flasher sslmode=disable

## Example
The project is a RESTful api for accessing the postgres database host=127.0.0.1 port=5432 user=flasher dbname=flasher password=flasher sslmode=disable.

## Project Files
The generated project will contain the following code under the `./example` directory.
* Makefile
  * useful Makefile for installing tools building project etc. Issue `make` to display help
* .gitignore
  * git ignore for go project
* go.mod
  * go module setup, pass `--module` flag for setting the project module default `example.com/example`
* README.md
  * Project readme
* app/server/main.go
  * Sample Gin Server, with swagger init and comments
* api/*.go
  * REST crud controllers
* dao/*.go
  * DAO functions providing CRUD access to database
* model/*.go
  * Structs representing a row for each database table

The REST api server utilizes the Gin framework, GORM db api and Swag for providing swagger documentation
* [Gin](https://github.com/gin-gonic/gin)
* [Swaggo](https://github.com/swaggo/swag)
* [Gorm](https://github.com/jinzhu/gorm)

## Building
```.bash
make example
```
Will create a binary `./bin/example`

## Running
```.bash
./bin/example
```
This will launch the web server on localhost:8080

## Swagger
The swagger web ui contains the documentation for the http server, it also provides an interactive interface to exercise the api and view results.
http://localhost:8080/swagger/index.html

## REST urls for fetching data


* http://localhost:8080/albumcosplayer
* http://localhost:8080/albums
* http://localhost:8080/categories
* http://localhost:8080/categorizables
* http://localhost:8080/comments
* http://localhost:8080/contacts
* http://localhost:8080/cosplayers
* http://localhost:8080/invitations
* http://localhost:8080/media
* http://localhost:8080/migrations
* http://localhost:8080/pages
* http://localhost:8080/posts
* http://localhost:8080/settings
* http://localhost:8080/socialmedia
* http://localhost:8080/testimonials
* http://localhost:8080/users

## Project Generated Details
```.bash
gen \
    --sqltype=postgres \
    --connstr \
    host=127.0.0.1 port=5432 user=flasher dbname=flasher password=flasher sslmode=disable \
    --database \
    flasher \
    --json \
    --gorm \
    --guregu \
    --out \
    ./apps/api-go/example \
    --module \
    github.com/agravelot/flasher \
    --mod \
    --server \
    --makefile \
    --json-fmt=snake \
    --generate-proj \
    --overwrite \
    --exclude=telescope_entries,telescope_entries_tags,telescope_monitoring,users_save,failed_jobs
```











