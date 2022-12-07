package postgres

import (
	"api-go/domain/article"
	"api-go/model"
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

func (r PostgresArticleRepository) List(ctx context.Context, params article.ListParams) ([]model.Article, error) {
	qb := query.Use(r.storage.DB).Article
	q := qb.WithContext(ctx)

	if !params.IncludePrivate {
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

func (r PostgresArticleRepository) GetBySlug(ctx context.Context, params article.GetBySlugParams) (model.Article, error) {
	qb := query.Use(r.storage.DB).Article
	q := qb.WithContext(ctx)

	q.Where(qb.Slug.Eq(params.Slug))

	if !params.IncludePrivate {
		q = q.Scopes(published(query.Use(r.storage.DB)))
	}

	a, err := q.First()
	if err != nil {
		return model.Article{}, err
	}

	return *a, err
}

func (r PostgresArticleRepository) Create(ctx context.Context, params article.CreateParams) (model.Article, error) {
	qb := query.Use(r.storage.DB).Article
	q := qb.WithContext(ctx)

	err := q.Create(&params.Article)
	if err != nil {
		// TODO Cast pg error to have clean check
		if err.Error() == "ERROR: duplicate key value violates unique constraint \"idx_articles_slug\" (SQLSTATE 23505)" {
			return model.Article{}, article.ErrAlreadyExists
		}
		return model.Article{}, err
	}

	return params.Article, nil
}

func (r PostgresArticleRepository) Update(ctx context.Context, params article.UpdateParams) (model.Article, error) {
	qb := query.Use(r.storage.DB).Article
	q := qb.WithContext(ctx)

	err := q.Save(&params.Article)
	if err != nil {
		return model.Article{}, err
	}

	return params.Article, nil
}

func (r PostgresArticleRepository) Delete(ctx context.Context, params article.DeleteParams) error {
	qb := query.Use(r.storage.DB).Article
	q := qb.WithContext(ctx)

	rs, err := q.Where(qb.ID.Eq(int64(params.ID))).Delete()
	if err != nil {
		return fmt.Errorf("unable to delete article: %w", err)
	}
	if rs.RowsAffected == 0 {
		return article.ErrNotFound
	}

	return nil
}

func NewArticleRepository(storage *Postgres) (article.Repository, error) {
	return PostgresArticleRepository{storage}, nil
}
