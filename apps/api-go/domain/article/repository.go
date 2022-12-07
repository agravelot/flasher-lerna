package article

import (
	"context"

	"api-go/model"
)

type ListParams struct {
	Next           *int32
	Limit          *int32
	IncludePrivate bool
}

type GetBySlugParams struct {
	Slug           string
	IncludePrivate bool
}

type CreateParams struct {
	Article model.Article
}

type UpdateParams struct {
	Article model.Article
}

type DeleteParams struct {
	ID int32
}

// TOTO if values are passed as pointer, no need to return them
type Repository interface {
	Close() error
	List(ctx context.Context, params ListParams) ([]model.Article, error)
	GetBySlug(ctx context.Context, params GetBySlugParams) (model.Article, error)
	Create(ctx context.Context, params CreateParams) (model.Article, error)
	Update(ctx context.Context, params UpdateParams) (model.Article, error)
	Delete(ctx context.Context, params DeleteParams) error
}
