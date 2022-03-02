package article

import (
	"api-go/api"
	"api-go/auth"
	"api-go/gormQuery"
	"api-go/model"
	"context"
	"errors"
	"fmt"

	"gorm.io/gen"
	"gorm.io/gorm"
)

// Service is a simple CRUD interface for user articles.
type Service interface {
	GetArticleList(ctx context.Context, params PaginationParams) (PaginatedArticles, error)
	GetArticle(ctx context.Context, slug string) (ArticleResponse, error)
	PostArticle(ctx context.Context, r ArticleRequest) (ArticleResponse, error)
	PutArticle(ctx context.Context, slug string, r ArticleRequest) (ArticleResponse, error)
	PatchArticle(ctx context.Context, slug string, r ArticleRequest) (ArticleResponse, error)
	DeleteArticle(ctx context.Context, slug string) error
}

type PaginationParams struct {
	Next  int32
	Limit int
}

type PaginatedArticles struct {
	Data []ArticleResponse `json:"data"`
	Meta api.MetaOld       `json:"meta"`
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
		return db.Where(q.Article.PublishedAt.IsNotNull())
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

	var articleResponse []ArticleResponse
	for _, article := range articles {
		// TODO add missing fields
		articleResponse = append(articleResponse, tramsform(*article))
	}

	return PaginatedArticles{
		Data: articleResponse,
		Meta: api.MetaOld{Total: total, Limit: params.Limit},
	}, nil
}

func tramsform(a model.Article) ArticleResponse {
	return ArticleResponse{
		ID:              a.ID,
		Slug:            a.Slug,
		Name:            a.Name,
		MetaDescription: a.MetaDescription,
		Content:         a.Content,
		PublishedAt:     a.PublishedAt,
	}
}

func (s *service) PostArticle(ctx context.Context, r ArticleRequest) (ArticleResponse, error) {
	user := auth.GetUserClaims(ctx)
	qb := gormQuery.Use(s.db).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return ArticleResponse{}, ErrNoAuth
	}

	if user.IsAdmin() == false {
		return ArticleResponse{}, ErrNotAdmin
	}

	if err := r.Validate(); err != nil {
		return ArticleResponse{}, err
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
			return ArticleResponse{}, ErrAlreadyExists
		}
		return ArticleResponse{}, err
	}

	return tramsform(a), nil
}

func (s *service) GetArticle(ctx context.Context, slug string) (ArticleResponse, error) {
	user := auth.GetUserClaims(ctx)

	qb := gormQuery.Use(s.db).Article
	query := qb.WithContext(ctx)

	query.Where(qb.Slug.Eq(slug))

	if user == nil || (user != nil && user.IsAdmin() == false) {
		query = query.Scopes(Published(gormQuery.Use(s.db)))
	}

	a, err := query.First()

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return ArticleResponse{}, ErrNotFound
	}

	return tramsform(*a), err
}

func (s *service) PutArticle(ctx context.Context, slug string, r ArticleRequest) (ArticleResponse, error) {
	user := auth.GetUserClaims(ctx)
	qb := gormQuery.Use(s.db).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return ArticleResponse{}, ErrNoAuth
	}

	if user.IsAdmin() == false {
		return ArticleResponse{}, ErrNotAdmin
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
		return ArticleResponse{}, err
	}

	err := query.Save(&a)
	if err != nil {
		return ArticleResponse{}, fmt.Errorf("unable update article: %w", err)
	}

	return tramsform(a), nil
}

func (s *service) PatchArticle(ctx context.Context, slug string, a ArticleRequest) (ArticleResponse, error) {
	return ArticleResponse{}, nil
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
