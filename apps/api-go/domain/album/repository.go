package album

import (
	"api-go/model"
	"api-go/pkg/auth"
	"context"
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
	List(ctx context.Context, user *auth.Claims, params ListParams) ([]*model.Album, error)
	GetBySlug(ctx context.Context, user *auth.Claims, slug string) (*model.Album, error)
	Create(ctx context.Context, user *auth.Claims, album model.Album) (*model.Album, error)
}
