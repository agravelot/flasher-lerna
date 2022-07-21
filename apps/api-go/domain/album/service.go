package album

import (
	"context"
	"errors"
	"fmt"
	"time"

	albumspb "api-go/gen/go/proto/albums/v2"
	categoriespb "api-go/gen/go/proto/categories/v2"
	mediaspb "api-go/gen/go/proto/medias/v2"
	"api-go/infrastructure/storage/postgres"
	"api-go/model"
	"api-go/pkg/auth"
	"api-go/query"

	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/status"
	"google.golang.org/protobuf/types/known/timestamppb"
	"gorm.io/gorm"
)

var (
	ErrAlreadyExists = status.Error(codes.AlreadyExists, "already exists")
	ErrNotFound      = status.Error(codes.NotFound, "not found")
	ErrNoAuth        = status.Error(codes.Unauthenticated, "not authenticated")
	ErrNotAdmin      = status.Error(codes.PermissionDenied, "not admin")
)

type service struct {
	albumspb.AlbumServiceServer
	// TODO Use storage.Storage interface
	storage    *postgres.Postgres
	repository Repository
}

func NewService(orm *postgres.Postgres, repository Repository) (albumspb.AlbumServiceServer, error) {
	return &service{
		storage:    orm,
		repository: repository,
	}, nil
}

func (s *service) Index(ctx context.Context, r *albumspb.IndexRequest) (*albumspb.IndexResponse, error) {
	user := auth.GetUserClaims(ctx)

	params := ListParams{
		Next:  r.Next,
		Limit: r.Limit,
	}
	if r.Joins != nil {
		params.Joins = ListJoinsParams{
			Categories: r.Joins.Categories,
			Medias:     r.Joins.Medias,
		}
	}

	albums, err := s.repository.List(ctx, user, params)
	if err != nil {
		return nil, fmt.Errorf("unable get albums: %w", err)
	}

	data := make([]*albumspb.AlbumResponse, 0, len(albums))
	for _, a := range albums {
		data = append(data, transformAlbumFromDB(*a))
	}

	return &albumspb.IndexResponse{
		Data: data,
	}, nil
}

// TODO bool include relation

func (s *service) GetBySlug(ctx context.Context, r *albumspb.GetBySlugRequest) (*albumspb.GetBySlugResponse, error) {
	user := auth.GetUserClaims(ctx)

	a, err := s.repository.GetBySlug(ctx, user, r.Slug)

	if errors.Is(err, gorm.ErrRecordNotFound) {
		return nil, ErrNotFound
	}

	if err != nil {
		return nil, fmt.Errorf("error get album by slug: %w", err)
	}

	// TODO add medias and categes
	return &albumspb.GetBySlugResponse{
		Album: transformAlbumFromDB(*a),
	}, err
}

func (s *service) Create(ctx context.Context, r *albumspb.CreateRequest) (*albumspb.CreateResponse, error) {
	// pretty.Log(r)

	user := auth.GetUserClaims(ctx)

	if user == nil {
		return nil, ErrNoAuth
	}

	isAdmin := user != nil && user.IsAdmin()
	if !isAdmin {
		return nil, ErrNotAdmin
	}

	if err := r.ValidateAll(); err != nil {
		return nil, err
	}

	var publishedAt *time.Time
	if r.PublishedAt != nil {
		p := r.PublishedAt.AsTime()
		publishedAt = &p
	}

	a, err := s.repository.Create(ctx, user, model.Album{
		Title:       r.Name,
		Slug:        r.Slug,
		Body:        &r.Content,
		SsoID:       &user.Sub,
		PublishedAt: publishedAt,
	})
	if err != nil {
		return nil, fmt.Errorf("unable create album: %w", err)
	}

	data := transformAlbumFromDB(*a)

	return &albumspb.CreateResponse{
		Id:                     data.Id,
		Slug:                   data.Slug,
		Title:                  data.Title,
		Content:                data.Content,
		MetaDescription:        data.MetaDescription,
		PublishedAt:            data.PublishedAt,
		AuthorId:               data.AuthorId,
		Private:                data.Private,
		UserId:                 data.UserId,
		CreatedAt:              data.CreatedAt,
		UpdatedAt:              data.UpdatedAt,
		NotifyUsersOnPublished: data.NotifyUsersOnPublished,
	}, nil
}

func (s *service) Update(ctx context.Context, r *albumspb.UpdateRequest) (*albumspb.UpdateResponse, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return nil, ErrNoAuth
	}

	isAdmin := user != nil && user.IsAdmin()
	if !isAdmin {
		return nil, ErrNotAdmin
	}

	if err := r.ValidateAll(); err != nil {
		return nil, err
	}

	var publishedAt *time.Time
	if r.PublishedAt != nil {
		p := r.PublishedAt.AsTime()
		publishedAt = &p
	}
	a, err := s.repository.Update(ctx, user, model.Album{
		ID:          r.Id,
		Title:       r.Name,
		Slug:        r.Slug,
		Body:        &r.Content,
		SsoID:       &user.Sub,
		PublishedAt: publishedAt,
	})

	if errors.Is(err, gorm.ErrRecordNotFound) {
		return nil, ErrNotFound
	}

	data := transformAlbumFromDB(*a)

	return &albumspb.UpdateResponse{
		Id:                     data.Id,
		Slug:                   data.Slug,
		Title:                  data.Title,
		Content:                data.Content,
		MetaDescription:        data.MetaDescription,
		PublishedAt:            data.PublishedAt,
		AuthorId:               data.AuthorId,
		Private:                data.Private,
		UserId:                 data.UserId,
		CreatedAt:              data.CreatedAt,
		UpdatedAt:              data.UpdatedAt,
		NotifyUsersOnPublished: data.NotifyUsersOnPublished,
	}, nil
}

// func (s *service) PatchAlbum(ctx context.Context, slug string, a AlbumUpdateRequest) (AlbumResponse, error) {
// 	return AlbumResponse{}, nil
// }

func (s *service) Delete(ctx context.Context, r *albumspb.DeleteRequest) (*albumspb.DeleteResponse, error) {

	user := auth.GetUserClaims(ctx)
	if user == nil {
		return nil, ErrNoAuth
	}

	isAdmin := user != nil && user.IsAdmin()
	if !isAdmin {
		return nil, ErrNotAdmin
	}

	qb := query.Use(s.storage.DB).Album

	ri, err := qb.WithContext(ctx).Where(qb.ID.Eq(r.Id)).Delete()
	if ri.RowsAffected == 0 {
		return nil, ErrNotFound
	}
	if err != nil {
		return nil, err
	}

	return nil, err
}

func transformMediaFromDB(media model.Medium) *mediaspb.Media {
	return &mediaspb.Media{
		Id:        media.ID,
		Name:      media.Name,
		FileName:  media.FileName,
		MimeType:  media.MimeType,
		Size:      media.Size,
		CreatedAt: &timestamppb.Timestamp{Seconds: int64(media.CreatedAt.Second())},
		UpdatedAt: &timestamppb.Timestamp{Seconds: int64(media.UpdatedAt.Second())},
		CustomProperties: &mediaspb.Media_CustomProperties{
			Height: media.CustomProperties.Height,
			Width:  media.CustomProperties.Width,
		},
		ResponsiveImages: &mediaspb.Media_ResponsiveImages{
			Responsive: &mediaspb.Media_Responsive{
				Urls:      media.ResponsiveImages.Responsive.Urls,
				Base64Svg: media.ResponsiveImages.Responsive.Base64Svg,
			},
		},
	}
}

func transformCategoryFromDB(c model.Category) *categoriespb.Category {
	return &categoriespb.Category{
		Id: c.ID,
	}
}

func transformAlbumFromDB(a model.Album) *albumspb.AlbumResponse {
	var mediasResponse []*mediaspb.Media
	if a.Medias != nil {
		var tmp []*mediaspb.Media
		for _, m := range a.Medias {
			tmp = append(tmp, transformMediaFromDB(m))
		}
		mediasResponse = tmp
	}

	var categoriesResponse []*categoriespb.Category
	if a.Categories != nil {
		var tmp []*categoriespb.Category
		for _, c := range a.Categories {
			tmp = append(tmp, transformCategoryFromDB(c))
		}
		categoriesResponse = tmp
	}

	var publishedAt *timestamppb.Timestamp
	if a.PublishedAt != nil {
		publishedAt = &timestamppb.Timestamp{Seconds: int64(a.PublishedAt.Second())}
	}

	var body string
	if a.Body != nil {
		body = *a.Body
	}

	return &albumspb.AlbumResponse{
		Id:                     a.ID,
		Slug:                   a.Slug,
		Title:                  a.Title,
		MetaDescription:        a.MetaDescription,
		Content:                body,
		PublishedAt:            publishedAt,
		Private:                a.Private,
		AuthorId:               *a.SsoID,
		UserId:                 a.UserID,
		CreatedAt:              &timestamppb.Timestamp{Seconds: int64(a.CreatedAt.Second())},
		UpdatedAt:              &timestamppb.Timestamp{Seconds: int64(a.UpdatedAt.Second())},
		NotifyUsersOnPublished: a.NotifyUsersOnPublished,
		Categories:             categoriesResponse,
		Medias:                 mediasResponse,
	}
}
