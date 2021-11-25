package album

import (
	"api-go/api"
	"api-go/auth"
	"context"
	"errors"

	"gorm.io/gorm"
)

// Service is a simple CRUD interface for user albums.
type Service interface {
	PostAlbum(ctx context.Context, p Album) (Album, error)
	GetAlbumList(ctx context.Context, params PaginationParams) (PaginatedAlbums, error)
	GetAlbum(ctx context.Context, slug string) (Album, error)
	PutAlbum(ctx context.Context, slug string, p Album) (Album, error)
	PatchAlbum(ctx context.Context, slug string, p Album) (Album, error)
	DeleteAlbum(ctx context.Context, slug string) error
}

type PaginationParams struct {
	Next  string
	Limit int
}

type PaginatedAlbums struct {
	Data []Album  `json:"data"`
	Meta api.Meta `json:"meta"`
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

func (s *service) GetAlbumList(ctx context.Context, params PaginationParams) (PaginatedAlbums, error) {
	user := auth.GetUserClaims(ctx)

	albums := []Album{}
	var total int64

	query := s.db.Model(&albums)

	if user == nil || (user != nil && user.IsAdmin() == false) {
		query = query.Scopes(Published)
	}

	// TODO Can run in goroutines ?
	query.Count(&total)
	query.Scopes(api.Paginate(params.Next, params.Limit)).Find(&albums)

	return PaginatedAlbums{
		Data: albums,
		Meta: api.Meta{Total: total, Limit: params.Limit},
	}, nil
}

func (s *service) PostAlbum(ctx context.Context, a Album) (Album, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return Album{}, ErrNoAuth
	}

	if user.IsAdmin() == false {
		return Album{}, ErrNotAdmin
	}

	a.SsoID = user.Sub

	if err := a.Validate(); err != nil {
		return Album{}, err
	}

	if err := s.db.Create(&a).Error; err != nil {
		// TODO Cast pg error to have clean check
		if err.Error() == "ERROR: duplicate key value violates unique constraint \"idx_albums_slug\" (SQLSTATE 23505)" {
			return Album{}, ErrAlreadyExists
		}
		return Album{}, err
	}

	return a, nil
}

func (s *service) GetAlbum(ctx context.Context, slug string) (Album, error) {
	user := auth.GetUserClaims(ctx)

	query := s.db.Where("slug = ?", slug)

	if user == nil || (user != nil && user.IsAdmin() == false) {
		query = query.Scopes(Published)
	}

	a := Album{}
	err := query.First(&a).Error

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return Album{}, ErrNotFound
	}

	return a, err
}

func (s *service) PutAlbum(ctx context.Context, slug string, a Album) (Album, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return Album{}, ErrNoAuth
	}

	if user.IsAdmin() == false {
		return Album{}, ErrNotAdmin
	}

	a.SsoID = user.Sub

	if err := a.Validate(); err != nil {
		return Album{}, err
	}

	s.db.Save(a)

	return a, nil
}

func (s *service) PatchAlbum(ctx context.Context, slug string, a Album) (Album, error) {
	return Album{}, nil
}

func (s *service) DeleteAlbum(ctx context.Context, slug string) error {
	if err := s.db.Where("slug = ?", slug).First(&Album{}).Error; err != nil {
		return ErrNotFound
	}

	err := s.db.Where("slug = ?", slug).Delete(&Album{}).Error
	if err != nil {
		return err
	}

	return nil
}
