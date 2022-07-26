package article

import (
	articles_pb "api-go/gen/go/proto/articles/v2"
	"api-go/model"
	"api-go/pkg/auth"
	"context"
	"errors"
	"fmt"
	"time"

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
	articles_pb.ArticleServiceServer
	repository Repository
}

func NewService(r Repository) articles_pb.ArticleServiceServer {
	return service{repository: r}
}

func transform(a model.Article) *articles_pb.ArticleResponse {
	var publishedAt *timestamppb.Timestamp
	if a.PublishedAt != nil {
		publishedAt = &timestamppb.Timestamp{Seconds: int64(a.PublishedAt.Second())}
	}

	return &articles_pb.ArticleResponse{
		Id:              a.ID,
		Slug:            a.Slug,
		Name:            a.Name,
		MetaDescription: a.MetaDescription,
		Content:         a.Content,
		PublishedAt:     publishedAt,
		AuthorId:        a.AuthorUUID,
	}
}

func (s service) Index(ctx context.Context, request *articles_pb.IndexRequest) (*articles_pb.IndexResponse, error) {
	user := auth.GetUserClaims(ctx)

	// TODO List params
	articles, err := s.repository.List(ctx, user, ListParams{
		Limit: request.Limit,
		Next:  request.Next,
	})
	if err != nil {
		return nil, fmt.Errorf("failed to list articles: %w", err)
	}

	data := make([]*articles_pb.ArticleResponse, 0, len(articles))
	for _, article := range articles {
		// TODO add missing fields
		data = append(data, transform(article))
	}

	return &articles_pb.IndexResponse{
		Data: data,
		//Meta: api.MetaOld{Total: total, Limit: params.Limit},
	}, nil
}

func (s service) GetBySlug(ctx context.Context, request *articles_pb.GetBySlugRequest) (*articles_pb.GetBySlugResponse, error) {
	user := auth.GetUserClaims(ctx)

	a, err := s.repository.GetBySlug(ctx, user, request.Slug)

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return &articles_pb.GetBySlugResponse{}, ErrNotFound
	}

	data := transform(a)
	return &articles_pb.GetBySlugResponse{
		Id:              data.Id,
		Slug:            data.Slug,
		Name:            data.Name,
		MetaDescription: data.MetaDescription,
		Content:         data.Content,
		PublishedAt:     data.PublishedAt,
		AuthorId:        data.AuthorId,
	}, err
}

func (s service) Create(ctx context.Context, request *articles_pb.CreateRequest) (*articles_pb.CreateResponse, error) {
	user := auth.GetUserClaims(ctx)

	if user == nil {
		return nil, ErrNoAuth
	}

	// TODO check if user is admin

	if err := request.ValidateAll(); err != nil {
		return nil, err
	}

	var p *time.Time
	if request.PublishedAt != nil {
		tmp := request.PublishedAt.AsTime()
		p = &tmp
	}

	a, err := s.repository.Create(ctx, user, model.Article{
		Slug:            request.Slug,
		Name:            request.Name,
		MetaDescription: request.MetaDescription,
		Content:         request.Content,
		PublishedAt:     p,
		AuthorUUID:      user.Sub,
	})

	data := transform(a)
	return &articles_pb.CreateResponse{
		Id:              data.Id,
		Slug:            data.Slug,
		Name:            data.Name,
		MetaDescription: data.MetaDescription,
		Content:         data.Content,
		PublishedAt:     data.PublishedAt,
		AuthorId:        data.AuthorId,
	}, err
}

func (s service) Update(ctx context.Context, request *articles_pb.UpdateRequest) (*articles_pb.UpdateResponse, error) {
	user := auth.GetUserClaims(ctx)

	p := request.PublishedAt.AsTime()

	if err := request.ValidateAll(); err != nil {
		return nil, err
	}

	// a.AuthorId = request.AuthorId
	a, err := s.repository.Update(ctx, user, model.Article{
		ID:              request.Id,
		Slug:            request.Slug,
		Name:            request.Name,
		MetaDescription: request.MetaDescription,
		Content:         request.Content,
		PublishedAt:     &p,
		// AuthorId : request.AuthorId
	})
	if err != nil {
		return &articles_pb.UpdateResponse{}, err
	}

	data := transform(a)
	return &articles_pb.UpdateResponse{
		Id:              data.Id,
		Slug:            data.Slug,
		Name:            data.Name,
		MetaDescription: data.MetaDescription,
		Content:         data.Content,
		PublishedAt:     data.PublishedAt,
		AuthorId:        data.AuthorId,
	}, nil
}

func (s service) Delete(ctx context.Context, request *articles_pb.DeleteRequest) (*articles_pb.DeleteResponse, error) {
	user := auth.GetUserClaims(ctx)
	// TODO check if user is admin and other stuffs
	err := s.repository.Delete(ctx, user, request.Id)

	return &articles_pb.DeleteResponse{}, err
}
