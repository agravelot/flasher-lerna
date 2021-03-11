package main

import (
	"api-go/blog/article"
	"api-go/config"
	database "api-go/db"
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

	db, _ := database.Init(config)
	db.AutoMigrate(&article.Article{})

	var s article.Service
	{
		s = article.NewService(db)
		s = article.LoggingMiddleware(logger)(s)
	}

	var h http.Handler
	{
		h = article.MakeHTTPHandler(s, log.With(logger, "component", "HTTP"))
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
