package main

import (
	album "api-go/albums"
	"api-go/article"
	"api-go/config"
	"api-go/database"
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

	orm, _ := database.Init(config)

	var sAlbum album.Service
	{
		sAlbum = album.NewService(orm)
		sAlbum = album.LoggingMiddleware(logger)(sAlbum)
	}

	var sArticle article.Service
	{
		sArticle = article.NewService(orm)
		sArticle = article.LoggingMiddleware(logger)(sArticle)
	}

	var h http.Handler
	{
		h = article.MakeHTTPHandler(sArticle, log.With(logger, "component", "HTTP"))
		h = album.MakeHTTPHandler(sAlbum, log.With(logger, "component", "HTTP"))
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
