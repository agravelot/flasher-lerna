package article

import (
	"api-go/api"
	"context"
	"errors"

	"gorm.io/gorm"
)

// Service is a simple CRUD interface for user articles.
type Service interface {
	PostArticle(ctx context.Context, p Article) error
	GetArticleList(ctx context.Context, params *PaginationParams) (PaginatedArticles, error)
	GetArticle(ctx context.Context, slug string) (Article, error)
	PutArticle(ctx context.Context, slug string, p Article) error
	PatchArticle(ctx context.Context, slug string, p Article) error
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
)

type service struct {
	db *gorm.DB
}

func NewService(db *gorm.DB) Service {
	return &service{
		db: db,
	}
}

func (s *service) GetArticleList(ctx context.Context, params *PaginationParams) (PaginatedArticles, error) {
	if params == nil {
		params = &PaginationParams{1, 10}
	}

	articles := []Article{}
	s.db.Scopes(api.Paginate(params.Page, params.PerPage)).Find(&articles)

	var total int64
	s.db.Model(&articles).Scopes(api.Paginate(params.Page, params.PerPage)).Count(&total)

	return PaginatedArticles{
		Data: articles,
		Meta: api.Meta{Total: total, PerPage: params.PerPage},
	}, nil
}

func (s *service) PostArticle(ctx context.Context, p Article) error {
	// s.mtx.Lock()
	// defer s.mtx.Unlock()
	// if _, ok := s.m[p.ID]; ok {
	// 	return ErrAlreadyExists // POST = create, don't overwrite
	// }
	// s.m[p.ID] = p
	return nil
}

func (s *service) GetArticle(ctx context.Context, slug string) (Article, error) {
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

func (s *service) PatchArticle(ctx context.Context, slug string, p Article) error {
	// if p.ID != "" && id != p.ID {
	// 	return ErrInconsistentIDs
	// }

	// s.mtx.Lock()
	// defer s.mtx.Unlock()

	// existing, ok := s.m[id]
	// if !ok {
	// 	return ErrNotFound // PATCH = update existing, don't create
	// }

	// // We assume that it's not possible to PATCH the ID, and that it's not
	// // possible to PATCH any field to its zero value. That is, the zero value
	// // means not specified. The way around this is to use e.g. Name *string in
	// // the Article definition. But since this is just a demonstrative example,
	// // I'm leaving that out.

	// if p.Name != "" {
	// 	existing.Name = p.Name
	// }
	// s.m[id] = existing
	return nil
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
