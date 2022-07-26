package article

import (
	"api-go/model"
	"api-go/pkg/auth"
	"context"
)

type ListParams struct {
	Next  *int32
	Limit *int32
}

// TOTO if values are passed as pointer, no need to return them
type Repository interface {
	Close() error
	List(ctx context.Context, user *auth.Claims, params ListParams) ([]model.Article, error)
	GetBySlug(ctx context.Context, user *auth.Claims, slug string) (model.Article, error)
	Create(ctx context.Context, user *auth.Claims, a model.Article) (model.Article, error)
	Update(ctx context.Context, user *auth.Claims, a model.Article) (model.Article, error)
	Delete(ctx context.Context, user *auth.Claims, id int32) error
}
