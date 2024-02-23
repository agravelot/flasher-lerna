package album

import (
	"context"

	"api-go/model"
)

type ListJoinsParams struct {
	Categories bool
	Medias     bool
}

type ListParams struct {
	Next           int32
	Limit          int32
	Joins          ListJoinsParams
	IncludePrivate bool
}

type GetByIDParams struct {
	ID             int32
	IncludePrivate bool
}

type GetBySlugParams struct {
	Slug           string
	IncludePrivate bool
}

type CreateParams struct {
	Album model.Album
}

type UpdateParams struct {
	Album model.Album
}

type DeleteParams struct {
	ID int32
}

type Repository interface {
	Close() error
	List(ctx context.Context, params ListParams) ([]model.Album, error)
	GetByID(ctx context.Context, params GetByIDParams) (model.Album, error)
	GetBySlug(ctx context.Context, params GetBySlugParams) (model.Album, error)
	Create(ctx context.Context, params CreateParams) (model.Album, error)
	Update(ctx context.Context, params UpdateParams) (model.Album, error)
	Delete(ctx context.Context, params DeleteParams) error
}
