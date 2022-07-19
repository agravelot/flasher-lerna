package article

import (
	articles_pb "api-go/gen/go/proto/articles/v2"
	"api-go/model"
	"api-go/pkg/auth"
	"api-go/query"
	"api-go/storage/postgres"
	"context"
	"errors"
	"fmt"
	"time"

	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/status"
	"google.golang.org/protobuf/types/known/timestamppb"

	"gorm.io/gen"
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
	// TODO Use storage.Storage interface
	db *postgres.Postgres
}

func NewService(db *postgres.Postgres) articles_pb.ArticleServiceServer {
	return service{
		db: db,
	}
}

func Published(q *query.Query) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		return db.Where(q.Article.PublishedAt.IsNotNull())
	}
}

func Paginate(q *query.Query, next *int32, pageSize *int32) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		if next != nil && *next != 0 {
			db = db.Where(q.Article.ID.Gt(int64(*next)))
		}
		s := 10
		if pageSize != nil {
			s = int(*pageSize)
		}
		return db.Limit(int(s))
	}
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

	qb := query.Use(s.db.DB).Article
	q := qb.WithContext(ctx)

	if user == nil || (user != nil && !user.IsAdmin()) {
		q = q.Scopes(Published(query.Use(s.db.DB)))
	}

	articles, err := q.Scopes(Paginate(query.Use(s.db.DB), request.Next, request.Limit)).Find()
	if err != nil {
		return &articles_pb.IndexResponse{}, fmt.Errorf("unable list articles: %w", err)
	}

	data := make([]*articles_pb.ArticleResponse, 0, len(articles))
	for _, article := range articles {
		// TODO add missing fields
		data = append(data, transform(*article))
	}

	return &articles_pb.IndexResponse{
		Data: data,
		//Meta: api.MetaOld{Total: total, Limit: params.Limit},
	}, nil
}

func (s service) GetBySlug(ctx context.Context, request *articles_pb.GetBySlugRequest) (*articles_pb.GetBySlugResponse, error) {
	user := auth.GetUserClaims(ctx)

	qb := query.Use(s.db.DB).Article
	q := qb.WithContext(ctx)

	q.Where(qb.Slug.Eq(request.Slug))

	if user == nil || (user != nil && !user.IsAdmin()) {
		q = q.Scopes(Published(query.Use(s.db.DB)))
	}

	a, err := q.First()

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return &articles_pb.GetBySlugResponse{}, ErrNotFound
	}

	data := transform(*a)
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

	qb := query.Use(s.db.DB).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return &articles_pb.CreateResponse{}, ErrNoAuth
	}

	if !user.IsAdmin() {
		return &articles_pb.CreateResponse{}, ErrNotAdmin
	}

	if err := request.ValidateAll(); err != nil {
		return &articles_pb.CreateResponse{}, err
	}

	var p *time.Time
	if request.PublishedAt != nil {
		tmp := request.PublishedAt.AsTime()
		p = &tmp
	}
	a := model.Article{
		Slug:            request.Slug,
		Name:            request.Name,
		MetaDescription: request.MetaDescription,
		Content:         request.Content,
		PublishedAt:     p,
		AuthorUUID:      user.Sub,
	}
	err := query.Create(&a)
	if err != nil {
		// TODO Cast pg error to have clean check
		if err.Error() == "ERROR: duplicate key value violates unique constraint \"idx_articles_slug\" (SQLSTATE 23505)" {
			return &articles_pb.CreateResponse{}, ErrAlreadyExists
		}
		return &articles_pb.CreateResponse{}, err
	}

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

	qb := query.Use(s.db.DB).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return &articles_pb.UpdateResponse{}, ErrNoAuth
	}

	if !user.IsAdmin() {
		return &articles_pb.UpdateResponse{}, ErrNotAdmin
	}

	if err := request.ValidateAll(); err != nil {
		return nil, err
	}

	a, err := query.Where(qb.Slug.Eq(request.Slug)).First()
	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return &articles_pb.UpdateResponse{}, ErrNotFound
	}

	p := request.PublishedAt.AsTime()
	a.ID = request.Id
	a.Slug = request.Slug
	a.Name = request.Name
	a.MetaDescription = request.MetaDescription
	a.Content = request.Content
	a.PublishedAt = &p
	// a.AuthorId = request.AuthorId

	err = query.Save(a)
	if err != nil {
		return &articles_pb.UpdateResponse{}, fmt.Errorf("unable update article: %w", err)
	}

	data := transform(*a)
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
	if err := s.db.DB.Where("id = ?", request.Id).First(&model.Article{}).Error; err != nil {
		return nil, ErrNotFound
	}

	err := s.db.DB.Where("id = ?", request.Id).Delete(&model.Article{}).Error
	return nil, err
}
