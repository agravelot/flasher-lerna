package postgres

import (
	"api-go/domain/album"
	"api-go/model"
	"api-go/pkg/auth"
	"api-go/query"
	"context"
	"fmt"
	"time"

	"gorm.io/gen"
)

// Postgres repository implmentation
type PostgresRepository struct {
	storage *Postgres
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

func (r PostgresRepository) List(ctx context.Context, user *auth.Claims, params album.ListParams) ([]model.Album, error) {
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

	res, err := q.Scopes(
		r.paginate(query.Use(r.storage.DB), params.Next, params.Limit),
	).Find()

	if err != nil {
		return []model.Album{}, fmt.Errorf("unable list albums : %d %w", params.Next, err)
	}

	albums := make([]model.Album, len(res))
	for i, a := range res {
		albums[i] = *a
	}

	return albums, nil
}

func (r PostgresRepository) GetBySlug(ctx context.Context, user *auth.Claims, slug string) (model.Album, error) {
	isAdmin := user != nil && user.IsAdmin()

	qb := query.Use(r.storage.DB).Album

	query := qb.WithContext(ctx)

	if !isAdmin {
		query = query.Where(qb.PublishedAt.Lt(time.Now()), qb.Private.Is(false))
	}

	res, err := query.
		Preload(qb.Categories).
		Preload(qb.Medias).
		Where(qb.Slug.Eq(slug)).
		First()

	if err != nil {
		return model.Album{}, fmt.Errorf("error get album: %w", err)
	}

	return *res, err
}

func (r PostgresRepository) Create(ctx context.Context, user *auth.Claims, a model.Album) (model.Album, error) {

	// if r.Slug == nil {
	// 	s := slug.Make(r.Title)
	// 	r.Slug = &s
	// }

	query := query.Use(r.storage.DB).Album.WithContext(ctx)

	err := query.WithContext(ctx).Create(&a)
	// TODO Check duplicate
	if err != nil {
		return model.Album{}, err
	}

	return a, nil
}

func (r PostgresRepository) Update(ctx context.Context, user *auth.Claims, a model.Album) (model.Album, error) {
	qb := query.Use(r.storage.DB).Album

	query := qb.WithContext(ctx)

	update := map[string]interface{}{
		// "id":           r.Id,
		"private":      a.Private,
		"title":        a.Title,
		"slug":         a.Slug,
		"body":         a.Title,
		"published_at": a.PublishedAt,
	}
	_, err := query.Where(qb.ID.Eq(a.ID)).Updates(update)
	if err != nil {
		return model.Album{}, fmt.Errorf("error update album: %w", err)
	}
	query.Preload(qb.Categories).Preload(qb.Medias)

	res, err := query.Where(qb.ID.Eq(a.ID)).First()

	if err != nil {
		return model.Album{}, fmt.Errorf("error get album: %w", err)
	}

	return *res, err
}

func (r PostgresRepository) Delete(ctx context.Context, user *auth.Claims, id int32) error {
	qb := query.Use(r.storage.DB).Album

	ri, err := qb.WithContext(ctx).Where(qb.ID.Eq(id)).Delete()
	if ri.RowsAffected == 0 {
		return album.ErrNotFound
	}
	return err
}

func NewAlbumRepository(storage *Postgres) (album.Repository, error) {
	return PostgresRepository{storage}, nil
}
