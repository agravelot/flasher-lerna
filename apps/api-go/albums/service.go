package album

import (
	"api-go/api"
	"api-go/auth"
	"api-go/tutorial"
	"context"
	"database/sql"
	"errors"
	"fmt"
	"time"

	"github.com/google/uuid"
	"github.com/jackc/pgx/v4"
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

	arg := tutorial.GetAlbumsParams{
		Limit: params.Limit,
	}

	if params.Next != 0 {
		arg.ID = int32(params.Next)
	}

	isAdmin := user != nil && user.IsAdmin()

	if isAdmin {
		arg.IsAdmin = true
	}

	albums, err := s.db.GetAlbums(ctx, arg)
	if err != nil {
		return PaginatedAlbums{}, fmt.Errorf("error list albums : %d %w", params.Next, err)
	}

	total, err := s.db.CountAlbums(ctx, isAdmin)
	if err != nil {
		return PaginatedAlbums{}, fmt.Errorf("error counting albums: %w", err)
	}

	var categories *[]tutorial.GetCategoriesByAlbumIdsRow

	if params.Joins.Categories {
		var ids []int32

		for _, a := range albums {
			ids = append(ids, a.ID)
		}

		c, err := s.db.GetCategoriesByAlbumIds(ctx, ids)
		if err != nil {
			return PaginatedAlbums{}, fmt.Errorf("error getting categories for albums: %w", err)
		}
		categories = &c

		// TODO Filter categories by album ids
	}

	var medias *[]tutorial.Medium

	if params.Joins.Medias {
		var ids []int32

		for _, a := range albums {
			ids = append(ids, a.ID)
		}

		m, err := s.db.GetMediasByAlbumIds(ctx, ids)
		if err != nil {
			return PaginatedAlbums{}, fmt.Errorf("error getting medias for albums: %w", err)
		}
		medias = &m
		// TODO Filter categories by album ids
	}

	data := make([]AlbumResponse, len(albums))
	for i, a := range albums {
		data[i] = transform(a, medias, categories)
	}

	return PaginatedAlbums{
		Data: data,
		Meta: api.Meta{Total: total, Limit: params.Limit},
	}, nil
}

func transform(a tutorial.Album, medias *[]tutorial.Medium, categories *[]tutorial.GetCategoriesByAlbumIdsRow) AlbumResponse {
	var body *string
	var userID *int64
	var ssoID uuid.UUID
	var publishedAt, createdAt, updatedAt *time.Time
	if a.Body.Valid {
		body = &a.Body.String
	}
	if a.SsoID.Valid {
		ssoID = a.SsoID.UUID
	}
	if a.UserID.Valid {
		userID = &a.UserID.Int64
	}
	if a.PublishedAt.Valid {
		publishedAt = &a.PublishedAt.Time
	}
	if a.CreatedAt.Valid {
		createdAt = &a.CreatedAt.Time
	}
	if a.UpdatedAt.Valid {
		updatedAt = &a.UpdatedAt.Time
	}

	var mediasResponse *[]MediaReponse
	if medias != nil {
		var tmp []MediaReponse
		for _, c := range *medias {
			tmp = append(tmp, MediaReponse{
				ID:   c.ID,
				Name: c.Name,
			})
		}
		mediasResponse = &tmp
	}

	var categoriesResponse *[]CategoryReponse
	if categories != nil {
		var tmp2 []CategoryReponse
		for _, c := range *categories {
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
		Body:                   body,
		PublishedAt:            publishedAt,
		Private:                a.Private,
		SsoID:                  ssoID,
		UserID:                 userID,
		CreatedAt:              createdAt,
		UpdatedAt:              updatedAt,
		NotifyUsersOnPublished: a.NotifyUsersOnPublished,
		Categories:             categoriesResponse,
		Medias:                 mediasResponse,
	}
}

func (s *service) GetAlbum(ctx context.Context, slug string) (AlbumResponse, error) {
	user := auth.GetUserClaims(ctx)

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

	isAdmin := user != nil && user.IsAdmin()

	a, err := s.db.GetAlbumBySlug(ctx, tutorial.GetAlbumBySlugParams{
		Slug:    slug,
		IsAdmin: isAdmin,
	})

	if err == pgx.ErrNoRows {
		return AlbumResponse{}, ErrNotFound
	}

	if err != nil {
		return AlbumResponse{}, fmt.Errorf("error get album by slug: %w", err)
	}

	// TODO add medias and categes
	return transform(a, nil, nil), err
}

func (s *service) PostAlbum(ctx context.Context, a AlbumRequest) (AlbumResponse, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return AlbumResponse{}, ErrNoAuth
	}

	isAdmin := user != nil && user.IsAdmin()
	if !isAdmin {
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
		body.String = *a.Body
		body.Valid = true
	}

	var ssoID uuid.NullUUID
	if a.SsoID != nil {
		ssoID.UUID = *a.SsoID
		ssoID.Valid = true
	}

	var publishedAt sql.NullTime
	if a.PublishedAt != nil {
		publishedAt.Time = *a.PublishedAt
		publishedAt.Valid = true
	}

	arg := tutorial.CreateAlbumParams{
		Slug:            a.Slug,
		Title:           a.Title,
		MetaDescription: a.MetaDescription,
		Body:            body,
		PublishedAt:     publishedAt,
		Private:         a.Private,
		SsoID:           ssoID,
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

	return transform(a2, nil, nil), nil
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
		Slug:            a.Slug,
		Title:           a.Title,
		MetaDescription: a.MetaDescription,
		Body:            body,
		PublishedAt:     publishedAt,
		Private:         a.Private,
		SsoID:           ssoID,
	}

	a2, err := s.db.GetAlbumBySlug(ctx, tutorial.GetAlbumBySlugParams{
		Slug:    slug,
		IsAdmin: isAdmin,
	})

	if err == pgx.ErrNoRows {
		return AlbumResponse{}, ErrNotFound
	}

	err = s.db.UpdateAlbum(ctx, arg)
	if err != nil {
		return AlbumResponse{}, err
	}

	// albumModel := AlbumModel(a)
	// s.db.Save(albumModel)

	return transform(a2, nil, nil), nil
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
