package album

import (
	"api-go/api"
	"api-go/auth"
	"api-go/ent"
	"api-go/ent/album"
	"api-go/ent/predicate"
	"api-go/tutorial"
	"context"
	"database/sql"
	"errors"
	"fmt"
	"time"

	"github.com/davecgh/go-spew/spew"
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
	db  *tutorial.Queries
	orm *ent.Client
}

func NewService(db *tutorial.Queries, orm *ent.Client) Service {
	return &service{
		db:  db,
		orm: orm,
	}
}

// func Published(db *tutorial.Queries) *tutorial.Queries {
// 	return db.Where("published_at < ?", time.Now()).Where("private = ?", false)
// }

func (s *service) GetAlbumList(ctx context.Context, params AlbumListParams) (PaginatedAlbums, error) {
	user := auth.GetUserClaims(ctx)

	query := s.orm.Album.Query().Limit(int(params.Limit))

	// arg := tutorial.GetAlbumsParams{
	// 	Limit: params.Limit,
	// }

	if params.Next != 0 {
		// arg.ID = int32(params.Next)
		query.Where(album.IDGT(int32(params.Next)))
	}

	isAdmin := user != nil && user.IsAdmin()

	if !isAdmin {
		// arg.IsAdmin = true
		query.Where(album.Private(false), album.PublishedAtLT(time.Now()))
	}

	albums, err := query.WithCategories().All(ctx)
	spew.Dump(albums)
	if err != nil {
		return PaginatedAlbums{}, fmt.Errorf("error list albums : %d %w", params.Next, err)
	}

	total, err := query.Count(ctx)
	if err != nil {
		return PaginatedAlbums{}, fmt.Errorf("error counting albums: %w", err)
	}

	data := make([]AlbumResponse, len(albums))
	// for i, a := range albums {
	// 	data[i] = transform(a, medias, categories)
	// }

	return PaginatedAlbums{
		Data: data,
		Meta: api.Meta{Total: int64(total), Limit: params.Limit},
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

	isAdmin := user != nil && user.IsAdmin()

	where := []predicate.Album{album.Slug(slug)}

	if !isAdmin {
		where = append(where, album.Private(false), album.PublishedAtLT(time.Now()))
	}

	a, err := s.orm.Album.Query().Where(where...).First(ctx)

	if ent.IsNotFound(err) {
		return AlbumResponse{}, ErrNotFound
	}

	if err != nil {
		return AlbumResponse{}, fmt.Errorf("error get album by slug: %w", err)
	}

	// TODO add medias and categes
	return AlbumResponse{
		ID:          a.ID,
		Slug:        a.Slug,
		Title:       a.Title,
		Body:        &a.Body,
		PublishedAt: &a.PublishedAt,
		Private:     a.Private,
		// UserID:      int64(a.UserID),
	}, err
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
