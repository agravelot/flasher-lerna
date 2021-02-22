package blog

import (
	"api-go/api"
	"context"
	"errors"
	"time"

	"gorm.io/gorm"
)

// Service is a simple CRUD interface for user blogs.
type Service interface {
	PostBlog(ctx context.Context, p Blog) error
	GetBlogList(ctx context.Context) (PaginatedBlogs, error)
	GetBlog(ctx context.Context, slug string) (Blog, error)
	PutBlog(ctx context.Context, slug string, p Blog) error
	PatchBlog(ctx context.Context, slug string, p Blog) error
	DeleteBlog(ctx context.Context, slug string) error
}

// Blog represents a single user blog.
// ID should be globally unique.
type Blog struct {
	ID        uint           `gorm:"primarykey" json:"id"`
	Slug      string         `json:"slug"`
	Name      string         `json:"name"`
	Content   string         `json:"content"`
	CreatedAt time.Time      `json:"created_at"`
	UpdatedAt time.Time      `json:"updated_at"`
	DeletedAt gorm.DeletedAt `gorm:"index"`
}

type PaginatedBlogs struct {
	Data []Blog   `json:"data"`
	Meta api.Meta `json:"meta"`
}

var (
	ErrInconsistentIDs = errors.New("inconsistent IDs")
	ErrAlreadyExists   = errors.New("already exists")
	ErrNotFound        = errors.New("not found")
)

type service struct {
	db *gorm.DB
}

func NewService(db *gorm.DB) Service {
	return &service{
		db: db,
	}
}

func (s *service) GetBlogList(ctx context.Context) (PaginatedBlogs, error) {
	// s.mtx.RLock()
	// defer s.mtx.RUnlock()

	// b := make([]Blog, 0, len(s.m))
	// for _, v := range s.m {
	// 	b = append(b, v)
	// }

	blogs := []Blog{}
	s.db.Scopes(api.Paginate(1, 10)).Find(&blogs)

	var total int64
	s.db.Model(&blogs).Scopes(api.Paginate(1, 10)).Count(&total)

	return PaginatedBlogs{
		Data: blogs,
		Meta: api.Meta{Total: total, PerPage: 10},
	}, nil
}

func (s *service) PostBlog(ctx context.Context, p Blog) error {
	// s.mtx.Lock()
	// defer s.mtx.Unlock()
	// if _, ok := s.m[p.ID]; ok {
	// 	return ErrAlreadyExists // POST = create, don't overwrite
	// }
	// s.m[p.ID] = p
	return nil
}

func (s *service) GetBlog(ctx context.Context, slug string) (Blog, error) {
	b := Blog{}
	err := s.db.Where("slug = ?", slug).First(&b).Error

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return Blog{}, ErrNotFound
	}

	return b, err

	// s.mtx.RLock()
	// defer s.mtx.RUnlock()
	// p, ok := s.m[id]
	// if !ok {
	// 	return Blog{}, ErrNotFound
	// }
	// return p, nil
}

func (s *service) PutBlog(ctx context.Context, slug string, p Blog) error {
	// if id != p.ID {
	// 	return ErrInconsistentIDs
	// }
	// s.mtx.Lock()
	// defer s.mtx.Unlock()
	// s.m[id] = p // PUT = create or update
	return nil
}

func (s *service) PatchBlog(ctx context.Context, slug string, p Blog) error {
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
	// // the Blog definition. But since this is just a demonstrative example,
	// // I'm leaving that out.

	// if p.Name != "" {
	// 	existing.Name = p.Name
	// }
	// s.m[id] = existing
	return nil
}

func (s *service) DeleteBlog(ctx context.Context, slug string) error {
	// s.mtx.Lock()
	// defer s.mtx.Unlock()
	// if _, ok := s.m[id]; !ok {
	// 	return ErrNotFound
	// }
	// delete(s.m, id)
	return nil
}
