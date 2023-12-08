package postgres

import (
	"context"
	"fmt"
	"github.com/kr/pretty"
	"time"

	"api-go/domain/album"
	"api-go/model"
	"api-go/query"

	"gorm.io/gen"
)

// AlbumRepository Postgres repository implementation.
type AlbumRepository struct {
	storage *Postgres
}

func (r *AlbumRepository) published(q *query.Query) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		// TODO try use NOW()
		return db.Where(q.Album.Private.Is(false), q.Album.PublishedAt.Lte(time.Now()))
	}
}

func (r AlbumRepository) paginate(q *query.Query, next int32, pageSize int32) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		if next != 0 {
			db = db.Where(q.Album.ID.Gt(next))
		}
		s := 10
		if pageSize != 0 {
			s = int(pageSize)
		}
		return db.Limit(int(s))
	}
}

func (r AlbumRepository) Close() error {
	return r.storage.Close()
}

func (r AlbumRepository) List(ctx context.Context, params album.ListParams) ([]model.Album, error) {
	qb := query.Use(r.storage.DB).Album
	q := qb.WithContext(ctx).Order(qb.PublishedAt.Desc())

	if !params.IncludePrivate {
		q = q.Scopes(r.published(query.Use(r.storage.DB)))
	}

	if params.Next != 0 {
		q.Where(qb.ID.Gt(params.Next))
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

func (r AlbumRepository) GetBySlug(ctx context.Context, params album.GetBySlugParams) (model.Album, error) {

	qb := query.Use(r.storage.DB).Album

	q := qb.WithContext(ctx)

	if !params.IncludePrivate {
		q = q.Scopes(r.published(query.Use(r.storage.DB)))
	}

	res, err := q.
		Preload(qb.Categories).
		Preload(qb.Medias).
		Where(qb.Slug.Eq(params.Slug)).
		First()
	if err != nil {
		return model.Album{}, fmt.Errorf("error get album: %w", err)
	}

	return *res, err
}

func (r AlbumRepository) Create(ctx context.Context, params album.CreateParams) (model.Album, error) {
	// if r.Slug == nil {
	// 	s := slug.Make(r.Title)
	// 	r.Slug = &s
	// }

	q := query.Use(r.storage.DB).Album.WithContext(ctx)

	pretty.Log(params.Album.Body)

	err := q.WithContext(ctx).Create(&params.Album)
	// TODO Check duplicate
	if err != nil {
		return model.Album{}, fmt.Errorf("error create album: %w", err)
	}

	return params.Album, nil
}

func (r AlbumRepository) Update(ctx context.Context, params album.UpdateParams) (model.Album, error) {
	qb := query.Use(r.storage.DB).Album

	q := qb.WithContext(ctx)

	update := map[string]interface{}{
		// "id":           r.Id,
		"private":      params.Album.Private,
		"title":        params.Album.Title,
		"slug":         params.Album.Slug,
		"body":         params.Album.Title,
		"published_at": params.Album.PublishedAt,
	}

	// TODO Test if result info is zero, return not found
	_, err := q.Where(qb.ID.Eq(params.Album.ID)).Updates(update)
	if err != nil {
		return model.Album{}, fmt.Errorf("error update album: %w", err)
	}
	q.Preload(qb.Categories).Preload(qb.Medias)

	res, err := q.Where(qb.ID.Eq(params.Album.ID)).First()
	if err != nil {
		return model.Album{}, fmt.Errorf("error get album: %w", err)
	}

	return *res, nil
}

func (r AlbumRepository) Delete(ctx context.Context, params album.DeleteParams) error {
	qb := query.Use(r.storage.DB).Album

	ri, err := qb.WithContext(ctx).Where(qb.ID.Eq(params.ID)).Delete()
	if ri.RowsAffected == 0 {
		return album.ErrNotFound
	}
	return err
}

func NewAlbumRepository(storage *Postgres) (album.Repository, error) {
	return AlbumRepository{storage}, nil
}
