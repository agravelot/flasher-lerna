package blog

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

func (mw loggingMiddleware) PostBlog(ctx context.Context, p Blog) (err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "PostBlog", "id", p.ID, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.PostBlog(ctx, p)
}

func (mw loggingMiddleware) GetBlogList(ctx context.Context) (p PaginatedBlogs, err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "GetBlogList", "page", 1, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.GetBlogList(ctx)
}

func (mw loggingMiddleware) GetBlog(ctx context.Context, id string) (p Blog, err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "GetBlog", "id", id, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.GetBlog(ctx, id)
}

func (mw loggingMiddleware) PutBlog(ctx context.Context, id string, p Blog) (err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "PutBlog", "id", id, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.PutBlog(ctx, id, p)
}

func (mw loggingMiddleware) PatchBlog(ctx context.Context, id string, p Blog) (err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "PatchBlog", "id", id, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.PatchBlog(ctx, id, p)
}

func (mw loggingMiddleware) DeleteBlog(ctx context.Context, id string) (err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "DeleteBlog", "id", id, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.DeleteBlog(ctx, id)
}
