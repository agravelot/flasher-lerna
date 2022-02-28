package album

import (
	"api-go/api"
	"api-go/auth"
	"api-go/gormQuery"
	"api-go/model"
	"api-go/tutorial"
	"context"
	"database/sql"
	"errors"
	"fmt"
	"time"

	"github.com/google/uuid"
	"github.com/gosimple/slug"
	"github.com/jackc/pgx/v4"
	"gorm.io/gorm"
)

// Service is a simple CRUD interface for user albums.
type Service interface {
	GetAlbumList(ctx context.Context, params AlbumListParams) (PaginatedAlbums, error)
	GetAlbum(ctx context.Context, slug string) (AlbumResponse, error)
	PostAlbum(ctx context.Context, p AlbumRequest) (AlbumResponse, error)
	PutAlbum(ctx context.Context, slug string, p AlbumRequest) (AlbumResponse, error)
	PatchAlbum(ctx context.Context, slug string, p AlbumRequest) (AlbumResponse, error)
	DeleteAlbum(ctx context.Context, slug string) error
}

var (
	ErrAlreadyExists = errors.New("already exists")
	ErrNotFound      = errors.New("not found")
	ErrNoAuth        = errors.New("not authenticated")
	ErrNotAdmin      = errors.New("not admin")
)

type service struct {
	db  *tutorial.Queries
	orm *gorm.DB
}

func NewService(db *tutorial.Queries, orm *gorm.DB) Service {
	return &service{
		db:  db,
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
		query.Where(qb.ID.Gt(int64(params.Next)))
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
		data[i] = transform(*a)
	}

	return PaginatedAlbums{
		Data: data,
		Meta: api.Meta{Total: total, Limit: params.Limit},
	}, nil
}

// TODO bool incluce relation
func transform(a model.Album) AlbumResponse {
	var mediasResponse *[]MediaReponse
	if a.Medias != nil {
		var tmp []MediaReponse
		for _, c := range a.Medias {
			tmp = append(tmp, MediaReponse{
				ID:   c.ID,
				Name: c.Name,
			})
		}
		mediasResponse = &tmp
	}

	var categoriesResponse *[]CategoryReponse
	if a.Categories != nil {
		var tmp2 []CategoryReponse
		for _, c := range a.Categories {
			tmp2 = append(tmp2, CategoryReponse{
				ID:   c.ID,
				Name: c.Name,
			})
		}
		categoriesResponse = &tmp2
	}

	return AlbumResponse{
		ID:                     a.ID,
		Slug:                   a.Slug,
		Title:                  a.Title,
		MetaDescription:        a.MetaDescription,
		Body:                   a.Body,
		PublishedAt:            a.PublishedAt,
		Private:                a.Private,
		SsoID:                  a.SsoID,
		UserID:                 a.UserID,
		CreatedAt:              a.CreatedAt,
		UpdatedAt:              a.UpdatedAt,
		NotifyUsersOnPublished: a.NotifyUsersOnPublished,
		Medias:                 mediasResponse,
		Categories:             categoriesResponse,
	}
}

func (s *service) GetAlbum(ctx context.Context, slug string) (AlbumResponse, error) {
	user := auth.GetUserClaims(ctx)
	isAdmin := user != nil && user.IsAdmin()

	qb := gormQuery.Use(s.orm).Album

	query := qb.WithContext(ctx)

	if !isAdmin {
		query = query.Where(qb.PublishedAt.Lt(time.Now()), qb.Private.Is(false))
	}

	query.Preload(qb.Categories).Preload(qb.Medias)

	a, err := query.Where(qb.Slug.Eq(slug)).First()

	if errors.Is(err, gorm.ErrRecordNotFound) {
		return AlbumResponse{}, ErrNotFound
	}

	if err != nil {
		return AlbumResponse{}, fmt.Errorf("error get album by slug: %w", err)
	}

	// TODO add medias and categes
	return transform(*a), err
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

	qb := gormQuery.Use(s.orm).Album

	query := qb.WithContext(ctx)

	err := query.WithContext(ctx).Create(&album)
	// TODO Check duplicate
	if err != nil {
		return AlbumResponse{}, err
	}

	return transform(album), nil

}

func (s *service) PutAlbum(ctx context.Context, slug string, a AlbumRequest) (AlbumResponse, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return AlbumResponse{}, ErrNoAuth
	}

	isAdmin := user != nil && user.IsAdmin()
	if isAdmin {
		return AlbumResponse{}, ErrNotAdmin
	}

	uid, err := uuid.Parse(user.Sub)
	if err != nil {
		return AlbumResponse{}, err
	}

	a.SsoID = &uid

	if err := a.Validate(); err != nil {
		return AlbumResponse{}, err
	}

	var body sql.NullString
	if a.Body != nil {
		body = sql.NullString{String: *a.Body, Valid: true}
	}

	var publishedAt sql.NullTime
	if a.PublishedAt != nil {
		publishedAt = sql.NullTime{Time: *a.PublishedAt, Valid: true}
	}

	var ssoID uuid.NullUUID
	if a.SsoID != nil {
		ssoID = uuid.NullUUID{UUID: *a.SsoID, Valid: true}
	}
	arg := tutorial.UpdateAlbumParams{
		Slug:            *a.Slug,
		Title:           a.Title,
		MetaDescription: a.MetaDescription,
		Body:            body,
		PublishedAt:     publishedAt,
		Private:         a.Private,
		SsoID:           ssoID,
	}

	// a2, err := s.db.GetAlbumBySlug(ctx, tutorial.GetAlbumBySlugParams{
	// 	Slug:    slug,
	// 	IsAdmin: isAdmin,
	// })

	if err == pgx.ErrNoRows {
		return AlbumResponse{}, ErrNotFound
	}

	err = s.db.UpdateAlbum(ctx, arg)
	if err != nil {
		return AlbumResponse{}, err
	}

	// albumModel := AlbumModel(a)
	// s.db.Save(albumModel)

	// return transform(a2), nil
	return AlbumResponse{}, nil
}

func (s *service) PatchAlbum(ctx context.Context, slug string, a AlbumRequest) (AlbumResponse, error) {
	return AlbumResponse{}, nil
}

func (s *service) DeleteAlbum(ctx context.Context, slug string) error {

	if _, err := s.db.GetAlbumBySlug(ctx, tutorial.GetAlbumBySlugParams{
		Slug:    slug,
		IsAdmin: false, // TODO check if user is admin
	}); err != nil {
		return ErrNotFound
	}

	err := s.db.DeleteAlbum(ctx, slug)
	if err != nil {
		return err
	}

	return nil
}
