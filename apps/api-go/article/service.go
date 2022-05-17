package article

import (
	"api-go/auth"
	articlespb "api-go/gen/go/proto/articles/v1"
	"api-go/model"
	"api-go/query"
	"context"
	"errors"
	"fmt"
	"time"

	"google.golang.org/protobuf/types/known/timestamppb"

	"gorm.io/gen"
	"gorm.io/gorm"
)

var (
	ErrAlreadyExists = errors.New("already exists")
	ErrNotFound      = errors.New("not found")
	ErrNoAuth        = errors.New("not authenticated")
	ErrNotAdmin      = errors.New("not admin")
)

type Service struct {
	db *gorm.DB
}

func NewService(db *gorm.DB) Service {
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

func tramsform(a model.Article) *articlespb.ArticleResponse {
	var publishedAt *timestamppb.Timestamp
	if a.PublishedAt != nil {
		publishedAt = &timestamppb.Timestamp{Seconds: int64(a.PublishedAt.Second())}
	}

	return &articlespb.ArticleResponse{
		Id:              a.ID,
		Slug:            a.Slug,
		Name:            a.Name,
		MetaDescription: a.MetaDescription,
		Content:         a.Content,
		PublishedAt:     publishedAt,
		AuthorUuid:      a.AuthorUUID,
	}
}

func (s Service) Index(ctx context.Context, request *articlespb.IndexRequest) (*articlespb.IndexResponse, error) {
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
		return &articlespb.IndexResponse{}, err
	}

	var articleResponse []*articlespb.ArticleResponse
	for _, article := range articles {
		// TODO add missing fields
		articleResponse = append(articleResponse, tramsform(*article))
	}

	return &articlespb.IndexResponse{
		Data: articleResponse,
		//Meta: api.MetaOld{Total: total, Limit: params.Limit},
	}, nil
}

func (s Service) GetBySlug(ctx context.Context, request *articlespb.GetBySlugRequest) (*articlespb.GetBySlugResponse, error) {
	user := auth.GetUserClaims(ctx)

	qb := query.Use(s.db).Article
	q := qb.WithContext(ctx)

	q.Where(qb.Slug.Eq(request.Slug))

	if user == nil || (user != nil && !user.IsAdmin()) {
		q = q.Scopes(Published(query.Use(s.db)))
	}

	a, err := q.First()

	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return &articlespb.GetBySlugResponse{}, ErrNotFound
	}

	return &articlespb.GetBySlugResponse{
		Article: tramsform(*a),
	}, err
}

func (s Service) Create(ctx context.Context, request *articlespb.CreateRequest) (*articlespb.CreateResponse, error) {
	user := auth.GetUserClaims(ctx)

	qb := query.Use(s.db).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return &articlespb.CreateResponse{}, ErrNoAuth
	}

	if !user.IsAdmin() {
		return &articlespb.CreateResponse{}, ErrNotAdmin
	}

	if err := request.Article.ValidateAll(); err != nil {
		return &articlespb.CreateResponse{}, err
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
		AuthorUUID:      user.Sub,
	}
	err := query.Create(&a)
	if err != nil {
		// TODO Cast pg error to have clean check
		if err.Error() == "ERROR: duplicate key value violates unique constraint \"idx_articles_slug\" (SQLSTATE 23505)" {
			return &articlespb.CreateResponse{}, ErrAlreadyExists
		}
		return &articlespb.CreateResponse{}, err
	}

	return &articlespb.CreateResponse{
		Article: tramsform(a),
	}, nil
}

func (s Service) Update(ctx context.Context, request *articlespb.UpdateRequest) (*articlespb.UpdateResponse, error) {
	user := auth.GetUserClaims(ctx)

	qb := query.Use(s.db).Article
	query := qb.WithContext(ctx)

	if user == nil {
		return &articlespb.UpdateResponse{}, ErrNoAuth
	}

	if !user.IsAdmin() {
		return &articlespb.UpdateResponse{}, ErrNotAdmin
	}

	a, err := query.Where(qb.Slug.Eq(request.Article.Slug)).First()
	if err != nil && errors.Is(err, gorm.ErrRecordNotFound) {
		return &articlespb.UpdateResponse{}, ErrNotFound
	}

	p := request.Article.PublishedAt.AsTime()
	a.ID = request.Article.Id
	a.Slug = request.Article.Slug
	a.Name = request.Article.Name
	a.MetaDescription = request.Article.MetaDescription
	a.Content = request.Article.Content
	a.PublishedAt = &p
	// a.AuthorUUID = request.Article.AuthorUUID

	// if err := r.Validate(); err != nil {
	// 	return &articlespb.UpdateResponse{}, err
	// }

	err = query.Save(a)
	if err != nil {
		return &articlespb.UpdateResponse{}, fmt.Errorf("unable update article: %w", err)
	}

	return &articlespb.UpdateResponse{
		Article: tramsform(*a),
	}, nil
}

func (s Service) Delete(ctx context.Context, request *articlespb.DeleteRequest) (*articlespb.DeleteResponse, error) {
	if err := s.db.Where("id = ?", request.Id).First(&model.Article{}).Error; err != nil {
		return &articlespb.DeleteResponse{}, ErrNotFound
	}

	err := s.db.Where("id = ?", request.Id).Delete(&model.Article{}).Error
	return nil, err
}
