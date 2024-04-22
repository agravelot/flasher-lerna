package album

import (
	"api-go/infrastructure/auth"
	"api-go/model"
	"context"
	"errors"
	"fmt"
	"time"

	albumspb "api-go/gen/go/albums/v2"
	categoriespb "api-go/gen/go/categories/v2"
	mediaspb "api-go/gen/go/medias/v2"

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
	repository Repository
}

// NewService Create a new instance.
func NewService(r Repository) (albumspb.AlbumServiceServer, error) {
	return &service{
		repository: r,
	}, nil
}

func (s *service) Index(ctx context.Context, req *albumspb.IndexRequest) (*albumspb.IndexResponse, error) {
	var next, limit int32

	_, user := auth.GetUser(ctx)

	if req.Next != nil {
		next = *req.Next
	}

	if req.Limit != nil {
		limit = *req.Limit
	}

	params := ListParams{
		Next:  next,
		Limit: limit,
		Joins: ListJoinsParams{
			Categories: req.Joins.GetCategories(),
			Medias:     req.Joins.GetMedias(),
		},
		IncludePrivate: user.IsAdmin(),
	}

	albums, err := s.repository.List(ctx, params)
	if err != nil {
		return nil, fmt.Errorf("unable get albums: %w", err)
	}

	data := make([]*albumspb.AlbumResponse, 0, len(albums))
	for _, a := range albums {
		data = append(data, transformAlbumFromDB(a))
	}

	return &albumspb.IndexResponse{
		Data: data,
	}, nil
}

// TODO bool include relation

func (s *service) GetBySlug(ctx context.Context, req *albumspb.GetBySlugRequest) (*albumspb.GetBySlugResponse, error) {
	_, user := auth.GetUser(ctx)

	alb, err := s.repository.GetBySlug(ctx, GetBySlugParams{
		Slug:           req.Slug,
		IncludePrivate: user.IsAdmin(),
	})

	if errors.Is(err, gorm.ErrRecordNotFound) {
		return nil, ErrNotFound
	}

	if err != nil {
		return nil, fmt.Errorf("error get album by slug: %w", err)
	}

	// TODO add medias and categories
	return &albumspb.GetBySlugResponse{
		Album: transformAlbumFromDB(alb),
	}, err
}

func (s *service) Create(ctx context.Context, req *albumspb.CreateRequest) (*albumspb.CreateResponse, error) {
	authenticated, user := auth.GetUser(ctx)

	if !authenticated {
		return nil, ErrNoAuth
	}

	isAdmin := user.IsAdmin()
	if !isAdmin {
		return nil, ErrNotAdmin
	}

	if err := req.ValidateAll(); err != nil {
		return nil, err
	}

	var publishedAt *time.Time
	if req.PublishedAt != nil {
		p := req.PublishedAt.AsTime()
		publishedAt = &p
	}

	a, err := s.repository.Create(ctx, CreateParams{
		Album: model.Album{
			Title:       req.Name,
			Slug:        req.Slug,
			Body:        &req.Content,
			SsoID:       &user.Subject,
			PublishedAt: publishedAt,
		},
	})
	if err != nil {
		return nil, fmt.Errorf("unable create album: %w", err)
	}

	data := transformAlbumFromDB(a)

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

func (s *service) Update(ctx context.Context, req *albumspb.UpdateRequest) (*albumspb.UpdateResponse, error) {
	authenticated, user := auth.GetUser(ctx)

	if !authenticated {
		return nil, ErrNoAuth
	}

	if isAdmin := user.IsAdmin(); !isAdmin {
		return nil, ErrNotAdmin
	}

	if err := req.ValidateAll(); err != nil {
		return nil, err
	}

	var publishedAt *time.Time
	if req.PublishedAt != nil {
		p := req.PublishedAt.AsTime()
		publishedAt = &p
	}

	a, err := s.repository.Update(ctx, UpdateParams{Album: model.Album{
		ID:          req.Id,
		Title:       req.Name,
		Slug:        req.Slug,
		Body:        &req.Content,
		SsoID:       &user.Subject,
		PublishedAt: publishedAt,
		Private:     &req.Private,
	}})

	if errors.Is(err, gorm.ErrRecordNotFound) {
		return nil, ErrNotFound
	}

	if err != nil {
		return nil, fmt.Errorf("unable update album: %w", err)
	}

	data := transformAlbumFromDB(a)

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

func (s *service) Delete(ctx context.Context, req *albumspb.DeleteRequest) (*albumspb.DeleteResponse, error) {
	authenticated, user := auth.GetUser(ctx)

	if !authenticated {
		return nil, ErrNoAuth
	}

	if !user.IsAdmin() {
		return nil, ErrNotAdmin
	}

	err := s.repository.Delete(ctx, DeleteParams{ID: req.Id})
	if err != nil {
		return &albumspb.DeleteResponse{Deleted: false}, fmt.Errorf("unable delete album: %w", err)
	}
	return &albumspb.DeleteResponse{Deleted: true}, nil
}

func transformMediaFromDB(media model.Medium) *mediaspb.Media {
	return &mediaspb.Media{
		Id:       media.ID,
		Name:     media.Name,
		FileName: media.FileName,
		MimeType: media.MimeType,
		Size:     media.Size,
		CreatedAt: &timestamppb.Timestamp{
			Seconds: int64(media.CreatedAt.Second()),
			Nanos:   int32(media.CreatedAt.Nanosecond()),
		},
		UpdatedAt: &timestamppb.Timestamp{
			Seconds: int64(media.UpdatedAt.Second()),
			Nanos:   int32(media.UpdatedAt.Nanosecond()),
		},
		CustomProperties: &mediaspb.Media_CustomProperties{
			Height: media.CustomProperties.Height,
			Width:  media.CustomProperties.Width,
		},
		// ResponsiveImages: &mediaspb.Media_ResponsiveImages{ //nolint:nosnakecase
		// 	Responsive: &mediaspb.Media_Responsive{ //nolint:nosnakecase
		// 		Urls:      media.ResponsiveImages.Responsive.Urls,
		// 		Base64Svg: media.ResponsiveImages.Responsive.Base64Svg,
		// 	},
		// },
	}
}

func transformCategoryFromDB(c model.Category) *categoriespb.Category {
	return &categoriespb.Category{
		Id: c.ID,
	}
}

func transformAlbumFromDB(alb model.Album) *albumspb.AlbumResponse {
	var mediasResponse []*mediaspb.Media
	if alb.Medias != nil {
		var tmp []*mediaspb.Media
		for _, m := range alb.Medias {
			tmp = append(tmp, transformMediaFromDB(m))
		}
		mediasResponse = tmp
	}

	var categoriesResponse []*categoriespb.Category
	if alb.Categories != nil {
		var tmp []*categoriespb.Category
		for _, c := range alb.Categories {
			tmp = append(tmp, transformCategoryFromDB(c))
		}
		categoriesResponse = tmp
	}

	var publishedAt *timestamppb.Timestamp
	if alb.PublishedAt != nil {
		publishedAt = &timestamppb.Timestamp{Seconds: int64(alb.PublishedAt.Second()), Nanos: int32(alb.PublishedAt.Nanosecond())}
	}

	var body string
	if alb.Body != nil {
		body = *alb.Body
	}

	var createdAt *timestamppb.Timestamp
	if alb.CreatedAt != nil {
		createdAt = &timestamppb.Timestamp{Seconds: int64(alb.CreatedAt.Second())}
	}

	var updatedAt *timestamppb.Timestamp
	if alb.CreatedAt != nil {
		createdAt = &timestamppb.Timestamp{Seconds: int64(alb.CreatedAt.Second())}
	}

	return &albumspb.AlbumResponse{
		Id:                     alb.ID,
		Slug:                   alb.Slug,
		Title:                  alb.Title,
		MetaDescription:        alb.MetaDescription,
		Content:                body,
		PublishedAt:            publishedAt,
		Private:                *alb.Private,
		AuthorId:               *alb.SsoID,
		UserId:                 alb.UserID,
		CreatedAt:              createdAt,
		UpdatedAt:              updatedAt,
		NotifyUsersOnPublished: *alb.NotifyUsersOnPublished,
		Categories:             categoriesResponse,
		Medias:                 mediasResponse,
	}
}
