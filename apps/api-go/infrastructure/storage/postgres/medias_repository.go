package postgres

import (
	"api-go/domain/media"
	"api-go/model"
	"api-go/query"
	"context"
	"fmt"
)

// MediaRepository Postgres repository implementation.
type MediaRepository struct {
	storage *Postgres
}

func (r MediaRepository) Close() error {
	return r.storage.Close()
}

func (r MediaRepository) Create(ctx context.Context, params media.CreateParams) (model.Medium, error) {
	q := query.Use(r.storage.DB).Medium.WithContext(ctx)

	err := q.WithContext(ctx).Create(&params.Media)
	// TODO Check duplicate
	if err != nil {
		return model.Medium{}, fmt.Errorf("error create media: %w", err)
	}

	return params.Media, nil
}

func (r MediaRepository) Delete(ctx context.Context, params media.DeleteParams) error {
	qb := query.Use(r.storage.DB).Medium

	ri, err := qb.WithContext(ctx).Where(qb.ID.Eq(params.ID)).Delete()
	if ri.RowsAffected == 0 {
		return media.ErrNotFound
	}
	return err
}

func NewMediaRepository(storage *Postgres) (media.Repository, error) {
	return MediaRepository{storage}, nil
}
