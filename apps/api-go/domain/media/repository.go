package media

import (
	"api-go/model"
	"context"
)

type CreateParams struct {
	Media model.Medium
}

type DeleteParams struct {
	ID int32
}

type Repository interface {
	Close() error
	Create(ctx context.Context, params CreateParams) (model.Medium, error)
	Delete(ctx context.Context, params DeleteParams) error
}
