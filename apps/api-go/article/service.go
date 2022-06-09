package article

import (
	"api-go/auth"
	articles_pb "api-go/gen/go/proto/articles/v1"
	"api-go/model"
	"api-go/query"
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

type Service struct {
	articles_pb.UnimplementedArticleServiceServer
	db *gorm.DB
}

func NewService(db *gorm.DB) articles_pb.ArticleServiceServer {
	return Service{
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
		AuthorId:        a.AuthorId,
	}
}

func (s Service) Index(ctx context.Context, request *articles_pb.IndexRequest) (*articles_pb.IndexResponse, error) {
	user := auth.GetUserClaims(ctx)

	qb := query.Use(s.db).Article
	q := qb.WithContext(ctx)

	if user == nil || (user != nil && !user.IsAdmin()) {
		q = q.Scopes(Published(query.Use(s.db)))
	}

	//total, err := q.Count()
	//if err != nil {
	//	return &articlespb.IndexResponse{}, err
	//}

	articles, err := q.Scopes(Paginate(query.Use(s.db), request.Next, request.Limit)).Find()
	if err != nil {
		return &articles_pb.IndexResponse{}, fmt.Errorf("unable list articles: %w", err)
	}

	data := make([]*articles_pb.ArticleResponse, len(articles))
	for _, article := range articles {
		// TODO add missing fields
		data = append(data, transform(*article))
	}

	return &articles_pb.IndexResponse{
		Data: data,
		//Meta: api.MetaOld{Total: total, Limit: params.Limit},
	}, nil
}

func (s Service) GetBySlug(ctx context.Context, request *articles_pb.GetBySlugRequest) (*articles_pb.GetBySlugResponse, error) {
	user := auth.GetUserClaims(ctx)

	qb := query.Use(s.db).Article
	q := qb.WithContext(ctx)

	q.Where(qb.Slug.Eq(request.Slug))

	if user == nil || (user != nil && !user.IsAdmin()) {
		q = q.Scopes(Published(query.Use(s.db)))
	}

	a, err := q.First()

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return &articles_pb.GetBySlugResponse{}, ErrNotFound
	}

	return &articles_pb.GetBySlugResponse{
		Article: transform(*a),
	}, err
}

func (s Service) Create(ctx context.Context, request *articles_pb.CreateRequest) (*articles_pb.CreateResponse, error) {
	user := auth.GetUserClaims(ctx)

	qb := query.Use(s.db).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return &articles_pb.CreateResponse{}, ErrNoAuth
	}

	if !user.IsAdmin() {
		return &articles_pb.CreateResponse{}, ErrNotAdmin
	}

	if err := request.Article.ValidateAll(); err != nil {
		return &articles_pb.CreateResponse{}, err
	}

	var p *time.Time
	if request.Article.PublishedAt != nil {
		tmp := request.Article.PublishedAt.AsTime()
		p = &tmp
	}
	a := model.Article{
		ID:              request.Article.Id,
		Slug:            request.Article.Slug,
		Name:            request.Article.Name,
		MetaDescription: request.Article.MetaDescription,
		Content:         request.Article.Content,
		PublishedAt:     p,
		AuthorId:        user.Sub,
	}
	err := query.Create(&a)
	if err != nil {
		// TODO Cast pg error to have clean check
		if err.Error() == "ERROR: duplicate key value violates unique constraint \"idx_articles_slug\" (SQLSTATE 23505)" {
			return &articles_pb.CreateResponse{}, ErrAlreadyExists
		}
		return &articles_pb.CreateResponse{}, err
	}

	return &articles_pb.CreateResponse{
		Article: transform(a),
	}, nil
}

func (s Service) Update(ctx context.Context, request *articles_pb.UpdateRequest) (*articles_pb.UpdateResponse, error) {
	user := auth.GetUserClaims(ctx)

	qb := query.Use(s.db).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return &articles_pb.UpdateResponse{}, ErrNoAuth
	}

	if !user.IsAdmin() {
		return &articles_pb.UpdateResponse{}, ErrNotAdmin
	}

	a, err := query.Where(qb.Slug.Eq(request.Article.Slug)).First()
	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return &articles_pb.UpdateResponse{}, ErrNotFound
	}

	p := request.Article.PublishedAt.AsTime()
	a.ID = request.Article.Id
	a.Slug = request.Article.Slug
	a.Name = request.Article.Name
	a.MetaDescription = request.Article.MetaDescription
	a.Content = request.Article.Content
	a.PublishedAt = &p
	// a.AuthorId = request.Article.AuthorId

	// if err := r.Validate(); err != nil {
	// 	return &articlespb.UpdateResponse{}, err
	// }

	err = query.Save(a)
	if err != nil {
		return &articles_pb.UpdateResponse{}, fmt.Errorf("unable update article: %w", err)
	}

	return &articles_pb.UpdateResponse{
		Article: transform(*a),
	}, nil
}

func (s Service) Delete(ctx context.Context, request *articles_pb.DeleteRequest) (*articles_pb.DeleteResponse, error) {
	if err := s.db.Where("id = ?", request.Id).First(&model.Article{}).Error; err != nil {
		return nil, ErrNotFound
	}

	err := s.db.Where("id = ?", request.Id).Delete(&model.Article{}).Error
	return nil, err
}
