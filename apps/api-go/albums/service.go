package album

import (
	"api-go/api"
	"api-go/auth"
	"api-go/tutorial"
	"context"
	"errors"
	"fmt"

	"github.com/google/uuid"
)

// Service is a simple CRUD interface for user albums.
type Service interface {
	GetAlbumList(ctx context.Context, params AlbumListParams) (PaginatedAlbums, error)
	GetAlbum(ctx context.Context, slug string) (AlbumResponse, error)
	PostAlbum(ctx context.Context, p AlbumResponse) (AlbumResponse, error)
	PutAlbum(ctx context.Context, slug string, p AlbumResponse) (AlbumResponse, error)
	PatchAlbum(ctx context.Context, slug string, p AlbumResponse) (AlbumResponse, error)
	DeleteAlbum(ctx context.Context, slug string) error
}

var (
	ErrAlreadyExists = errors.New("already exists")
	ErrNotFound      = errors.New("not found")
	ErrNoAuth        = errors.New("not authenticated")
	ErrNotAdmin      = errors.New("not admin")
)

type service struct {
	db *tutorial.Queries
}

func NewService(db *tutorial.Queries) Service {
	return &service{
		db: db,
	}
}

// func Published(db *tutorial.Queries) *tutorial.Queries {
// 	return db.Where("published_at < ?", time.Now()).Where("private = ?", false)
// }

func (s *service) GetAlbumList(ctx context.Context, params AlbumListParams) (PaginatedAlbums, error) {
	user := auth.GetUserClaims(ctx)

	// if params.Joins.Categories {
	// 	query = query.Preload("Categories")
	// }

	// if params.Joins.Medias {
	// 	query = query.Preload("Medias")
	// }

	// https://github.com/kyleconroy/sqlc/issues/1062
	// https://github.com/kyleconroy/sqlc/discussions/451
	// Admin

	arg := tutorial.GetAlbumsParams{
		Limit: params.Limit,
	}

	if params.Next != 0 {
		arg.ID = int32(params.Next)
	}

	if user != nil && user.IsAdmin() {
		arg.IsAdmin = true
	}

	albums, err := s.db.GetAlbums(ctx, arg)
	if err != nil {
		return PaginatedAlbums{}, fmt.Errorf("error list albums : %d %w", params.Next, err)
	}

	total, err := s.db.CountAlbums(ctx, user != nil && user.IsAdmin())
	if err != nil {
		return PaginatedAlbums{}, fmt.Errorf("error counting albums: %w", err)
	}

	data := make([]AlbumResponse, len(albums))
	for i, a := range albums {
		data[i] = AlbumResponse(a)
	}

	return PaginatedAlbums{
		Data: data,
		Meta: api.Meta{Total: total, Limit: params.Limit},
	}, nil
}

func (s *service) GetAlbum(ctx context.Context, slug string) (AlbumResponse, error) {
	// user := auth.GetUserClaims(ctx)

	// query := s.db.Where("slug = ?", slug)

	// if user == nil || (user != nil && !user.IsAdmin()) {
	// 	query = query.Scopes(Published)
	// }

	// a := AlbumModel{}
	// err := query.First(&a).Error

	// if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
	// 	return AlbumRequest{}, ErrNotFound
	// }
	// TODO https://github.com/jackc/pgerrcode/blob/master/errcode.go

	a, err := s.db.GetAlbumBySlug(ctx, slug)

	if err != nil {
		return AlbumResponse{}, err
	}

	return AlbumResponse(a), err
}

func (s *service) PostAlbum(ctx context.Context, a AlbumResponse) (AlbumResponse, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return AlbumResponse{}, ErrNoAuth
	}

	if !user.IsAdmin() {
		return AlbumResponse{}, ErrNotAdmin
	}

	uid, err := uuid.Parse(user.Sub)
	if err != nil {
		return AlbumResponse{}, err
	}

	a.SsoID = uuid.NullUUID{UUID: uid, Valid: true}

	if err := a.Validate(); err != nil {
		return AlbumResponse{}, err
	}

	arg := tutorial.CreateAlbumParams{
		Slug:            a.Slug,
		Title:           a.Title,
		MetaDescription: a.MetaDescription,
		Body:            a.Body,
		PublishedAt:     a.PublishedAt,
		Private:         a.Private,
		SsoID:           a.SsoID,
	}
	a2, err := s.db.CreateAlbum(ctx, arg)
	if err != nil {
		return AlbumResponse{}, err
	}

	// albumModel := AlbumModel(a)
	// if err := s.db.Create(&albumModel).Error; err != nil {
	// 	// TODO Cast pg error to have clean check
	// 	if err.Error() == "ERROR: duplicate key value violates unique constraint \"idx_albums_slug\" (SQLSTATE 23505)" {
	// 		return AlbumRequest{}, ErrAlreadyExists
	// 	}
	// 	return AlbumRequest{}, err
	// }

	// TODO https://github.com/jackc/pgerrcode/blob/master/errcode.go

	return AlbumResponse(a2), nil
}

func (s *service) PutAlbum(ctx context.Context, slug string, a AlbumResponse) (AlbumResponse, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return AlbumResponse{}, ErrNoAuth
	}

	if !user.IsAdmin() {
		return AlbumResponse{}, ErrNotAdmin
	}

	uid, err := uuid.Parse(user.Sub)
	if err != nil {
		return AlbumResponse{}, err
	}

	a.SsoID = uuid.NullUUID{UUID: uid, Valid: true}

	if err := a.Validate(); err != nil {
		return AlbumResponse{}, err
	}

	arg := tutorial.UpdateAlbumParams{
		Slug:            a.Slug,
		Title:           a.Title,
		MetaDescription: a.MetaDescription,
		Body:            a.Body,
		PublishedAt:     a.PublishedAt,
		Private:         a.Private,
		SsoID:           a.SsoID,
	}
	err = s.db.UpdateAlbum(ctx, arg)
	if err != nil {
		return AlbumResponse{}, err
	}

	// albumModel := AlbumModel(a)
	// s.db.Save(albumModel)

	return AlbumResponse(a), nil
}

func (s *service) PatchAlbum(ctx context.Context, slug string, a AlbumResponse) (AlbumResponse, error) {
	return AlbumResponse{}, nil
}

func (s *service) DeleteAlbum(ctx context.Context, slug string) error {

	if _, err := s.db.GetAlbumBySlug(ctx, slug); err != nil {
		return ErrNotFound
	}

	err := s.db.DeleteAlbum(ctx, slug)
	if err != nil {
		return err
	}

	return nil
}
