package main

import (
	album "api-go/albums"
	"api-go/config"
	"api-go/database"
	"api-go/database2"
	"fmt"
	"net/http"
	"os"
	"os/signal"
	"syscall"

	"github.com/go-kit/kit/log"
)

func main() {
	config := config.LoadDotEnv("")

	var logger log.Logger
	{
		logger = log.NewLogfmtLogger(os.Stderr)
		logger = log.With(logger, "ts", log.DefaultTimestampUTC)
		logger = log.With(logger, "caller", log.DefaultCaller)
	}

	db, _ := database2.Init(config)
	orm, _ := database.Init(config)

	// db.AutoMigrate(&article.Article{})
	// db.AutoMigrate(&album.MediaModel{})
	// db.AutoMigrate(&album.AlbumModel{})
	// db.AutoMigrate(&album.CategoryModel{})
	// db.AutoMigrate(&album.AlbumCategoryModel{})
	// err := db.SetupJoinTable(&album.AlbumModel{}, "Categories", &album.AlbumCategoryModel{})
	// if err != nil {
	// 	panic(err)
	// }
	// err = db.SetupJoinTable(&album.CategoryModel{}, "Albums", &album.AlbumCategoryModel{})
	// if err != nil {
	// 	panic(err)
	// }

	// var s article.Service
	// {
	// 	s = article.NewService(db)
	// 	s = article.LoggingMiddleware(logger)(s)
	// }

	var sa album.Service
	{
		sa = album.NewService(db, orm)
		sa = album.LoggingMiddleware(logger)(sa)
	}

	var h http.Handler
	{
		// h = article.MakeHTTPHandler(s, log.With(logger, "component", "HTTP"))
		h = album.MakeHTTPHandler(sa, log.With(logger, "component", "HTTP"))
	}

	errs := make(chan error)
	go func() {
		c := make(chan os.Signal)
		signal.Notify(c, syscall.SIGINT, syscall.SIGTERM)
		errs <- fmt.Errorf("%s", <-c)
	}()

	go func() {
		logger.Log("transport", "HTTP", "addr", config.Port)
		errs <- http.ListenAndServe(fmt.Sprintf(":%d", config.Port), h)
	}()

	logger.Log("exit", <-errs)
}
