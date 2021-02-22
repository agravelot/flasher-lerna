package article

import (
	"context"
	"time"

	"github.com/go-kit/kit/log"
)

// Middleware describes a service (as opposed to endpoint) middleware.
type Middleware func(Service) Service

func LoggingMiddleware(logger log.Logger) Middleware {
	return func(next Service) Service {
		return &loggingMiddleware{
			next:   next,
			logger: logger,
		}
	}
}

type loggingMiddleware struct {
	next   Service
	logger log.Logger
}

func (mw loggingMiddleware) PostArticle(ctx context.Context, a Article) (err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "PostArticle", "id", a.ID, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.PostArticle(ctx, a)
}

func (mw loggingMiddleware) GetArticleList(ctx context.Context) (p PaginatedArticles, err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "GetArticleList", "page", 1, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.GetArticleList(ctx)
}

func (mw loggingMiddleware) GetArticle(ctx context.Context, slug string) (p Article, err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "GetArticle", "slug", slug, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.GetArticle(ctx, slug)
}

func (mw loggingMiddleware) PutArticle(ctx context.Context, id string, p Article) (err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "PutArticle", "id", id, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.PutArticle(ctx, id, p)
}

func (mw loggingMiddleware) PatchArticle(ctx context.Context, id string, p Article) (err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "PatchArticle", "id", id, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.PatchArticle(ctx, id, p)
}

func (mw loggingMiddleware) DeleteArticle(ctx context.Context, id string) (err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "DeleteArticle", "id", id, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.DeleteArticle(ctx, id)
}
