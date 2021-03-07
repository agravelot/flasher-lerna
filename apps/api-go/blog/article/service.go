package article

import (
	"api-go/api"
	"api-go/blog/auth"
	"context"
	"errors"

	"gorm.io/gorm"
)

// Service is a simple CRUD interface for user articles.
type Service interface {
	PostArticle(ctx context.Context, p Article) (Article, error)
	GetArticleList(ctx context.Context, params *PaginationParams) (PaginatedArticles, error)
	GetArticle(ctx context.Context, slug string) (Article, error)
	PutArticle(ctx context.Context, slug string, p Article) error
	PatchArticle(ctx context.Context, slug string, p Article) (Article, error)
	DeleteArticle(ctx context.Context, slug string) error
}

type PaginationParams struct {
	Page    int
	PerPage int
}

type PaginatedArticles struct {
	Data []Article `json:"data"`
	Meta api.Meta  `json:"meta"`
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

func Published(db *gorm.DB) *gorm.DB {
	return db.Where("published_at is not null")
}

func (s *service) GetArticleList(ctx context.Context, params *PaginationParams) (PaginatedArticles, error) {

	if params == nil {
		params = &PaginationParams{1, 10}
	}

	user := auth.GetUserClaims(ctx)

	articles := []Article{}
	var total int64

	query := s.db.Model(&articles).Scopes(api.Paginate(params.Page, params.PerPage))

	if user == nil || (user != nil && user.IsAdmin() == false) {
		query = query.Scopes(Published)
	}

	query.Find(&articles).Count(&total)

	return PaginatedArticles{
		Data: articles,
		Meta: api.Meta{Total: total, PerPage: params.PerPage},
	}, nil
}

func (s *service) PostArticle(ctx context.Context, a Article) (Article, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return Article{}, ErrNoAuth
	}

	if user.IsAdmin() == false {
		return Article{}, ErrNotAdmin
	}

	a.AuthorUUID = user.Sub

	if err := a.Validate(); err != nil {
		return Article{}, err
	}

	if err := s.db.Create(&a).Error; err != nil {
		// TODO Cast pg error to have clean check
		if err.Error() == "ERROR: duplicate key value violates unique constraint \"idx_articles_slug\" (SQLSTATE 23505)" {
			return Article{}, ErrAlreadyExists
		}
		return Article{}, err
	}

	return a, nil
}

func (s *service) GetArticle(ctx context.Context, slug string) (Article, error) {
	// TODO 404 non published
	a := Article{}
	err := s.db.Where("slug = ?", slug).First(&a).Error

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return Article{}, ErrNotFound
	}

	return a, err
}

func (s *service) PutArticle(ctx context.Context, slug string, p Article) error {
	// if id != p.ID {
	// 	return ErrInconsistentIDs
	// }
	// s.mtx.Lock()
	// defer s.mtx.Unlock()
	// s.m[id] = p // PUT = create or update
	return nil
}

func (s *service) PatchArticle(ctx context.Context, slug string, a Article) (Article, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return Article{}, ErrNoAuth
	}

	if user.IsAdmin() == false {
		return Article{}, ErrNotAdmin
	}

	a.AuthorUUID = user.Sub

	if err := a.Validate(); err != nil {
		return Article{}, err
	}

	s.db.Save(a)

	return a, nil
}

func (s *service) DeleteArticle(ctx context.Context, slug string) error {
	if err := s.db.Where("slug = ?", slug).First(&Article{}).Error; err != nil {
		return ErrNotFound
	}

	err := s.db.Where("slug = ?", slug).Delete(&Article{}).Error
	if err != nil {
		return err
	}

	return nil
}
