package album

import (
	"api-go/api"
	"api-go/auth"
	"context"
	"errors"
	"fmt"
	"time"

	"gorm.io/gorm"
)

// Service is a simple CRUD interface for user albums.
type Service interface {
	GetAlbumList(ctx context.Context, params AlbumListParams) (PaginatedAlbums, error)
	GetAlbum(ctx context.Context, slug string) (AlbumRequest, error)
	PostAlbum(ctx context.Context, p AlbumRequest) (AlbumRequest, error)
	PutAlbum(ctx context.Context, slug string, p AlbumRequest) (AlbumRequest, error)
	PatchAlbum(ctx context.Context, slug string, p AlbumRequest) (AlbumRequest, error)
	DeleteAlbum(ctx context.Context, slug string) error
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
	return db.Where("published_at < ?", time.Now()).Where("private = ?", false)
}

func (s *service) GetAlbumList(ctx context.Context, params AlbumListParams) (PaginatedAlbums, error) {
	user := auth.GetUserClaims(ctx)

	albums := []AlbumModel{}
	var total int64

	query := s.db.Model(&albums)

	if user == nil || (user != nil && !user.IsAdmin()) {
		query = query.Scopes(Published)
	}

	if params.Joins.Categories {
		query = query.Preload("Categories")
	}

	if params.Joins.Medias {
		query = query.Preload("Medias")
	}

	// TODO Can run in goroutines ?
	err := query.Count(&total).Error
	if err != nil {
		return PaginatedAlbums{}, fmt.Errorf("error counting albums: %w", err)
	}
	err = query.Scopes(api.Paginate(params.Next, params.Limit)).Order("published_at DESC").Find(&albums).Error
	if err != nil {
		return PaginatedAlbums{}, fmt.Errorf("error list albums: %w", err)
	}

	data := make([]AlbumRequest, len(albums))
	for i, a := range albums {
		data[i] = AlbumRequest(a)
	}

	return PaginatedAlbums{
		Data: data,
		Meta: api.Meta{Total: total, Limit: params.Limit},
	}, nil
}

func (s *service) GetAlbum(ctx context.Context, slug string) (AlbumRequest, error) {
	user := auth.GetUserClaims(ctx)

	query := s.db.Where("slug = ?", slug)

	if user == nil || (user != nil && !user.IsAdmin()) {
		query = query.Scopes(Published)
	}

	a := AlbumModel{}
	err := query.First(&a).Error

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return AlbumRequest{}, ErrNotFound
	}

	return AlbumRequest(a), err
}

func (s *service) PostAlbum(ctx context.Context, a AlbumRequest) (AlbumRequest, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return AlbumRequest{}, ErrNoAuth
	}

	if !user.IsAdmin() {
		return AlbumRequest{}, ErrNotAdmin
	}

	a.SsoID = user.Sub

	if err := a.Validate(); err != nil {
		return AlbumRequest{}, err
	}

	albumModel := AlbumModel(a)
	if err := s.db.Create(&albumModel).Error; err != nil {
		// TODO Cast pg error to have clean check
		if err.Error() == "ERROR: duplicate key value violates unique constraint \"idx_albums_slug\" (SQLSTATE 23505)" {
			return AlbumRequest{}, ErrAlreadyExists
		}
		return AlbumRequest{}, err
	}

	return AlbumRequest(albumModel), nil
}

func (s *service) PutAlbum(ctx context.Context, slug string, a AlbumRequest) (AlbumRequest, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return AlbumRequest{}, ErrNoAuth
	}

	if !user.IsAdmin() {
		return AlbumRequest{}, ErrNotAdmin
	}

	a.SsoID = user.Sub

	if err := a.Validate(); err != nil {
		return AlbumRequest{}, err
	}

	albumModel := AlbumModel(a)
	s.db.Save(albumModel)

	return AlbumRequest(albumModel), nil
}

func (s *service) PatchAlbum(ctx context.Context, slug string, a AlbumRequest) (AlbumRequest, error) {
	return AlbumRequest{}, nil
}

func (s *service) DeleteAlbum(ctx context.Context, slug string) error {
	if err := s.db.Where("slug = ?", slug).First(&AlbumModel{}).Error; err != nil {
		return ErrNotFound
	}

	err := s.db.Where("slug = ?", slug).Delete(&AlbumModel{}).Error
	if err != nil {
		return err
	}

	return nil
}
