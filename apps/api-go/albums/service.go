package album

import (
	"api-go/api"
	"api-go/auth"
	"api-go/gormQuery"
	"api-go/model"
	"context"
	"errors"
	"fmt"
	"time"

	"github.com/gosimple/slug"
	"gorm.io/gorm"
)

// Service is a simple CRUD interface for user albums.
type Service interface {
	GetAlbumList(ctx context.Context, params AlbumListParams) (PaginatedAlbums, error)
	GetAlbum(ctx context.Context, slug string) (AlbumResponse, error)
	PostAlbum(ctx context.Context, p AlbumRequest) (AlbumResponse, error)
	PutAlbum(ctx context.Context, slug string, p AlbumRequest) (AlbumResponse, error)
	PatchAlbum(ctx context.Context, slug string, p AlbumUpdateRequest) (AlbumResponse, error)
	DeleteAlbum(ctx context.Context, slug string) error
}

var (
	ErrAlreadyExists = errors.New("already exists")
	ErrNotFound      = errors.New("not found")
	ErrNoAuth        = errors.New("not authenticated")
	ErrNotAdmin      = errors.New("not admin")
)

type service struct {
	orm *gorm.DB
}

func NewService(orm *gorm.DB) Service {
	return &service{
		orm: orm,
	}
}

func (s *service) GetAlbumList(ctx context.Context, params AlbumListParams) (PaginatedAlbums, error) {
	user := auth.GetUserClaims(ctx)
	isAdmin := user != nil && user.IsAdmin()

	qb := gormQuery.Use(s.orm).Album
	query := qb.WithContext(ctx).Order(qb.PublishedAt.Desc())

	if !isAdmin {
		query = query.Where(qb.PublishedAt.Lt(time.Now()), qb.Private.Is(false))
	}

	total, err := query.WithContext(ctx).Count()
	if err != nil {
		return PaginatedAlbums{}, fmt.Errorf("error counting albums: %w", err)
	}

	if params.Next != 0 {
		query.Where(qb.ID.Gt(params.Next))
	}

	if params.Joins.Categories {
		query.Preload(qb.Categories)
	}

	if params.Joins.Medias {
		query.Preload(qb.Medias)
	}

	albums, err := query.Limit(int(params.Limit)).Find()
	if err != nil {
		return PaginatedAlbums{}, fmt.Errorf("error list albums : %d %w", params.Next, err)
	}

	data := make([]AlbumResponse, len(albums))
	for i, a := range albums {
		data[i] = transformAlbumFromDB(*a)
	}

	return PaginatedAlbums{
		Data: data,
		Meta: api.Meta{Total: total, Limit: params.Limit},
	}, nil
}

// TODO bool incluce relation

func (s *service) GetAlbum(ctx context.Context, slug string) (AlbumResponse, error) {
	user := auth.GetUserClaims(ctx)
	isAdmin := user != nil && user.IsAdmin()

	qb := gormQuery.Use(s.orm).Album

	query := qb.WithContext(ctx)

	if !isAdmin {
		query = query.Where(qb.PublishedAt.Lt(time.Now()), qb.Private.Is(false))
	}

	a, err := query.
		Preload(qb.Categories).
		Preload(qb.Medias).
		Where(qb.Slug.Eq(slug)).
		First()

	if errors.Is(err, gorm.ErrRecordNotFound) {
		return AlbumResponse{}, ErrNotFound
	}

	if err != nil {
		return AlbumResponse{}, fmt.Errorf("error get album by slug: %w", err)
	}

	// TODO add medias and categes
	return transformAlbumFromDB(*a), err
}

func (s *service) PostAlbum(ctx context.Context, r AlbumRequest) (AlbumResponse, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return AlbumResponse{}, ErrNoAuth
	}

	isAdmin := user != nil && user.IsAdmin()
	if !isAdmin {
		return AlbumResponse{}, ErrNotAdmin
	}

	if err := r.Validate(); err != nil {
		return AlbumResponse{}, err
	}

	if r.Slug == nil {
		s := slug.Make(r.Title)
		r.Slug = &s
	}

	album := model.Album{
		Title:       r.Title,
		Slug:        *r.Slug,
		SsoID:       &user.Sub,
		Body:        r.Body,
		PublishedAt: r.PublishedAt,
	}

	query := gormQuery.Use(s.orm).Album.WithContext(ctx)

	err := query.WithContext(ctx).Create(&album)
	// TODO Check duplicate
	if err != nil {
		return AlbumResponse{}, err
	}

	return transformAlbumFromDB(album), nil

}

func (s *service) PutAlbum(ctx context.Context, slug string, r AlbumRequest) (AlbumResponse, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return AlbumResponse{}, ErrNoAuth
	}

	isAdmin := user != nil && user.IsAdmin()
	if !isAdmin {
		return AlbumResponse{}, ErrNotAdmin
	}

	if err := r.Validate(); err != nil {
		return AlbumResponse{}, err
	}

	qb := gormQuery.Use(s.orm).Album

	query := qb.WithContext(ctx)

	// TODO Check duplicate
	// TODO Check row count update
	// YODO UpdatedAt
	_, err := query.Updates(model.Album{
		ID:          r.ID,
		Slug:        slug,
		Title:       r.Title,
		Body:        r.Body,
		PublishedAt: r.PublishedAt,
		Private:     r.Private,
		SsoID:       &user.Sub,
	})
	if err != nil {
		return AlbumResponse{}, fmt.Errorf("error update album: %w", err)
	}

	query.Preload(qb.Categories).Preload(qb.Medias)

	a, err := query.Where(qb.Slug.Eq(slug)).First()

	if errors.Is(err, gorm.ErrRecordNotFound) {
		return AlbumResponse{}, ErrNotFound
	}

	return transformAlbumFromDB(*a), nil
}

func (s *service) PatchAlbum(ctx context.Context, slug string, a AlbumUpdateRequest) (AlbumResponse, error) {
	return AlbumResponse{}, nil
}

func (s *service) DeleteAlbum(ctx context.Context, slug string) error {

	user := auth.GetUserClaims(ctx)
	if user == nil {
		return ErrNoAuth
	}

	isAdmin := user != nil && user.IsAdmin()
	if !isAdmin {
		return ErrNotAdmin
	}

	qb := gormQuery.Use(s.orm).Album

	query := qb.WithContext(ctx)

	r, err := query.Where(qb.Slug.Eq(slug)).Delete()
	if r.RowsAffected == 0 {
		return ErrNotFound
	}
	if err != nil {
		return err
	}

	return nil
}
