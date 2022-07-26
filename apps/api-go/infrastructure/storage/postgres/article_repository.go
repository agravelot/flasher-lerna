package postgres

import (
	"api-go/domain/article"
	"api-go/model"
	"api-go/pkg/auth"
	"api-go/query"
	"context"
	"fmt"

	"gorm.io/gen"
)

// Postgres repository implmentation
type PostgresArticleRepository struct {
	storage *Postgres
}

func published(q *query.Query) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		return db.Where(q.Article.PublishedAt.IsNotNull())
	}
}

func paginate(q *query.Query, next *int32, pageSize *int32) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		if next != nil && *next != 0 {
			db = db.Where(q.Article.ID.Gt(int64(*next)))
		}
		s := 10
		if pageSize != nil {
			s = int(*pageSize)
		}
		return db.Limit(int(s))
	}
}

func (r PostgresArticleRepository) Close() error {
	return r.storage.Close()
}

func (r PostgresArticleRepository) List(ctx context.Context, user *auth.Claims, params article.ListParams) ([]model.Article, error) {
	qb := query.Use(r.storage.DB).Article
	q := qb.WithContext(ctx)

	if user == nil || (user != nil && !user.IsAdmin()) {
		q = q.Scopes(published(query.Use(r.storage.DB)))
	}

	articles, err := q.Scopes(paginate(query.Use(r.storage.DB), params.Next, params.Limit)).Find()
	if err != nil {
		return []model.Article{}, fmt.Errorf("unable list articles: %w", err)
	}

	a2 := make([]model.Article, len(articles))
	for i, a := range articles {
		a2[i] = *a
	}

	return a2, nil
}

func (r PostgresArticleRepository) GetBySlug(ctx context.Context, user *auth.Claims, slug string) (model.Article, error) {
	qb := query.Use(r.storage.DB).Article
	q := qb.WithContext(ctx)

	q.Where(qb.Slug.Eq(slug))

	if user == nil || (user != nil && !user.IsAdmin()) {
		q = q.Scopes(published(query.Use(r.storage.DB)))
	}

	a, err := q.First()
	if err != nil {
		return model.Article{}, err
	}

	return *a, err
}

func (r PostgresArticleRepository) Create(ctx context.Context, user *auth.Claims, a model.Article) (model.Article, error) {
	qb := query.Use(r.storage.DB).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return model.Article{}, article.ErrNoAuth
	}

	if !user.IsAdmin() {
		return model.Article{}, article.ErrNotAdmin
	}

	err := query.Create(&a)
	if err != nil {
		// TODO Cast pg error to have clean check
		if err.Error() == "ERROR: duplicate key value violates unique constraint \"idx_articles_slug\" (SQLSTATE 23505)" {
			return model.Article{}, article.ErrAlreadyExists
		}
		return model.Article{}, err
	}

	return a, nil
}

func (r PostgresArticleRepository) Update(ctx context.Context, user *auth.Claims, a model.Article) (model.Article, error) {
	qb := query.Use(r.storage.DB).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return model.Article{}, article.ErrNoAuth
	}

	if !user.IsAdmin() {
		return model.Article{}, article.ErrNotAdmin
	}

	err := query.Save(&a)
	if err != nil {
		return model.Article{}, err
	}

	return a, nil
}

func (r PostgresArticleRepository) Delete(ctx context.Context, user *auth.Claims, id int32) error {
	qb := query.Use(r.storage.DB).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return article.ErrNoAuth
	}

	if !user.IsAdmin() {
		return article.ErrNotAdmin
	}

	rs, err := query.Where(qb.ID.Eq(int64(id))).Delete()
	if rs.RowsAffected == 0 {
		return article.ErrNotFound
	}
	if err != nil {
		return err
	}

	return nil
}

func NewArticleRepository(storage *Postgres) (article.Repository, error) {
	return PostgresArticleRepository{storage}, nil
}
