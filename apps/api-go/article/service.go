package article

import (
	"api-go/api"
	"api-go/auth"
	"api-go/gormQuery"
	"api-go/model"
	"context"
	"errors"

	"gorm.io/gen"
	"gorm.io/gorm"
)

// Service is a simple CRUD interface for user articles.
type Service interface {
	GetArticleList(ctx context.Context, params PaginationParams) (PaginatedArticles, error)
	GetArticle(ctx context.Context, slug string) (model.Article, error)
	PostArticle(ctx context.Context, r ArticleRequest) (model.Article, error)
	PutArticle(ctx context.Context, slug string, r ArticleRequest) (model.Article, error)
	PatchArticle(ctx context.Context, slug string, r ArticleRequest) (model.Article, error)
	DeleteArticle(ctx context.Context, slug string) error
}

type PaginationParams struct {
	Next  int32
	Limit int
}

type PaginatedArticles struct {
	Data []*model.Article `json:"data"`
	Meta api.MetaOld      `json:"meta"`
}

var (
	ErrAlreadyExists = errors.New("already exists")
	ErrNotFound      = errors.New("not found")
	ErrNoAuth        = errors.New("not authenticated")
	ErrNotAdmin      = errors.New("not admin")
)

type service struct {
	db *gorm.DB
}

func NewService(db *gorm.DB) Service {
	return &service{
		db: db,
	}
}

func Published(q *gormQuery.Query) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		return db.Where(q.Album.PublishedAt.IsNotNull())
	}
}

func (s *service) GetArticleList(ctx context.Context, params PaginationParams) (PaginatedArticles, error) {
	user := auth.GetUserClaims(ctx)

	qb := gormQuery.Use(s.db).Article
	query := qb.WithContext(ctx)

	if user == nil || (user != nil && user.IsAdmin() == false) {
		query = query.Scopes(Published(gormQuery.Use(s.db)))
	}

	// TODO Can run in goroutines ?
	total, err := query.Count()
	if err != nil {
		return PaginatedArticles{}, err
	}
	articles, err := query.Scopes(api.Paginate(gormQuery.Use(s.db), params.Next, params.Limit)).Find()
	if err != nil {
		return PaginatedArticles{}, err
	}

	return PaginatedArticles{
		Data: articles,
		Meta: api.MetaOld{Total: total, Limit: params.Limit},
	}, nil
}

func (s *service) PostArticle(ctx context.Context, r ArticleRequest) (model.Article, error) {
	user := auth.GetUserClaims(ctx)
	qb := gormQuery.Use(s.db).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return model.Article{}, ErrNoAuth
	}

	if user.IsAdmin() == false {
		return model.Article{}, ErrNotAdmin
	}

	if err := r.Validate(); err != nil {
		return model.Article{}, err
	}

	a := model.Article{
		ID:              r.ID,
		Slug:            r.Slug,
		Name:            r.Name,
		MetaDescription: r.MetaDescription,
		Content:         r.Content,
		PublishedAt:     r.PublishedAt,
		AuthorUUID:      user.Sub,
	}
	err := query.Create(&a)
	if err != nil {
		// TODO Cast pg error to have clean check
		if err.Error() == "ERROR: duplicate key value violates unique constraint \"idx_articles_slug\" (SQLSTATE 23505)" {
			return model.Article{}, ErrAlreadyExists
		}
		return model.Article{}, err
	}

	return a, nil
}

func (s *service) GetArticle(ctx context.Context, slug string) (model.Article, error) {
	user := auth.GetUserClaims(ctx)

	qb := gormQuery.Use(s.db).Article
	query := qb.WithContext(ctx)

	query.Where(qb.Slug.Eq(slug))

	if user == nil || (user != nil && user.IsAdmin() == false) {
		query = query.Scopes(Published(gormQuery.Use(s.db)))
	}

	a, err := query.First()

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return model.Article{}, ErrNotFound
	}

	return *a, err
}

func (s *service) PutArticle(ctx context.Context, slug string, r ArticleRequest) (model.Article, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return model.Article{}, ErrNoAuth
	}

	if user.IsAdmin() == false {
		return model.Article{}, ErrNotAdmin
	}

	a := model.Article{
		ID:              r.ID,
		Slug:            r.Slug,
		Name:            r.Name,
		MetaDescription: r.MetaDescription,
		Content:         r.Content,
		PublishedAt:     r.PublishedAt,
		AuthorUUID:      user.Sub,
	}

	if err := r.Validate(); err != nil {
		return model.Article{}, err
	}

	s.db.Save(a)

	return a, nil
}

func (s *service) PatchArticle(ctx context.Context, slug string, a ArticleRequest) (model.Article, error) {
	return model.Article{}, nil
}

func (s *service) DeleteArticle(ctx context.Context, slug string) error {
	if err := s.db.Where("slug = ?", slug).First(&model.Article{}).Error; err != nil {
		return ErrNotFound
	}

	err := s.db.Where("slug = ?", slug).Delete(&model.Article{}).Error
	if err != nil {
		return err
	}

	return nil
}
