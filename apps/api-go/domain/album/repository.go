package album

import (
	"context"

	"api-go/model"
	"api-go/pkg/auth"
)

type ListJoinsParams struct {
	Categories bool
	Medias     bool
}

type ListParams struct {
	Next  *int32
	Limit *int32
	Joins ListJoinsParams
}

type Repository interface {
	Close() error
	// TODO Remove user from params
	List(ctx context.Context, user *auth.Claims, params ListParams) ([]model.Album, error)
	GetBySlug(ctx context.Context, user *auth.Claims, slug string) (model.Album, error)
	Create(ctx context.Context, user *auth.Claims, album model.Album) (model.Album, error)
	Update(ctx context.Context, user *auth.Claims, album model.Album) (model.Album, error)
	Delete(ctx context.Context, user *auth.Claims, id int32) error
}
