package album

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

func (mw loggingMiddleware) PostAlbum(ctx context.Context, ar AlbumRequest) (a AlbumRequest, err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "PostAlbum", "id", a.ID, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.PostAlbum(ctx, ar)
}

func (mw loggingMiddleware) GetAlbumList(ctx context.Context, params AlbumListParams) (p PaginatedAlbums, err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "GetAlbumList", "page", 1, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.GetAlbumList(ctx, params)
}

func (mw loggingMiddleware) GetAlbum(ctx context.Context, slug string) (p AlbumRequest, err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "GetAlbum", "slug", slug, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.GetAlbum(ctx, slug)
}

func (mw loggingMiddleware) PutAlbum(ctx context.Context, id string, ar AlbumRequest) (a AlbumRequest, err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "PutAlbum", "id", id, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.PutAlbum(ctx, id, ar)
}

func (mw loggingMiddleware) PatchAlbum(ctx context.Context, slug string, p AlbumRequest) (a AlbumRequest, err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "PatchAlbum", "slug", slug, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.PatchAlbum(ctx, slug, p)
}

func (mw loggingMiddleware) DeleteAlbum(ctx context.Context, id string) (err error) {
	defer func(begin time.Time) {
		mw.logger.Log("method", "DeleteAlbum", "id", id, "took", time.Since(begin), "err", err)
	}(time.Now())
	return mw.next.DeleteAlbum(ctx, id)
}
