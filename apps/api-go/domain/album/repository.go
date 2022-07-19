package album

import (
	"api-go/model"
	"api-go/pkg/auth"
	"api-go/query"
	"api-go/storage/postgres"
	"context"
	"fmt"
	"time"

	"gorm.io/gen"
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
}

// Postgres repository implmentation
type PostgresRepository struct {
	storage *postgres.Postgres
}

func (r *PostgresRepository) published(q *query.Query) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		return db.Where(q.Album.Private.Is(false), q.Album.PublishedAt.Lte(time.Now()))
	}
}

func (r PostgresRepository) paginate(q *query.Query, next *int32, pageSize *int32) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		if next != nil && *next != 0 {
			db = db.Where(q.Album.ID.Gt(*next))
		}
		s := 10
		if pageSize != nil {
			s = int(*pageSize)
		}
		return db.Limit(int(s))
	}
}

func (r PostgresRepository) Close() error {
	return r.storage.Close()
}

func (r PostgresRepository) List(ctx context.Context, user *auth.Claims, params ListParams) ([]*model.Album, error) {
	qb := query.Use(r.storage.DB).Album
	q := qb.WithContext(ctx).Order(qb.PublishedAt.Desc())

	if user == nil || (user != nil && !user.IsAdmin()) {
		q = q.Scopes(r.published(query.Use(r.storage.DB)))
	}

	if params.Next != nil && *params.Next != 0 {
		q.Where(qb.ID.Gt(*params.Next))
	}

	if params.Joins.Categories {
		q = q.Preload(qb.Categories)
	}

	if params.Joins.Medias {
		q = q.Preload(qb.Medias)
	}

	albums, err := q.Scopes(
		r.paginate(query.Use(r.storage.DB), params.Next, params.Limit),
	).Find()
	if err != nil {
		return albums, fmt.Errorf("unable list albums : %d %w", params.Next, err)
	}

	return albums, nil

}

func NewRepository(storage *postgres.Postgres) (Repository, error) {
	return PostgresRepository{storage}, nil
}
