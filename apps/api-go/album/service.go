package album

import (
	"api-go/auth"
	"api-go/model"
	"api-go/query"
	"context"
	"errors"
	"fmt"
	"time"

	albums_pb "api-go/gen/go/proto/albums/v2"

	"google.golang.org/grpc/codes"
	"google.golang.org/grpc/status"
	"gorm.io/gen"
	"gorm.io/gorm"
)

// // Service is a simple CRUD interface for user albums.
// type Service interface {
// 	Index(ctx context.Context, params albums_pb.IndexRequest) (albums_pb.IndexResponse, error)
// 	GetBySlug(ctx context.Context, slug string) (AlbumResponse, error)
// 	Create(ctx context.Context, p AlbumRequest) (AlbumResponse, error)
// 	Update(ctx context.Context, slug string, p AlbumRequest) (AlbumResponse, error)
// 	// PatchAlbum(ctx context.Context, slug string, p AlbumUpdateRequest) (AlbumResponse, error)
// 	Delete(ctx context.Context, slug string) error
// }

var (
	ErrAlreadyExists = status.Error(codes.AlreadyExists, "already exists")
	ErrNotFound      = status.Error(codes.NotFound, "not found")
	ErrNoAuth        = status.Error(codes.Unauthenticated, "not authenticated")
	ErrNotAdmin      = status.Error(codes.PermissionDenied, "not admin")
)

type service struct {
	albums_pb.AlbumServiceServer
	orm *gorm.DB
}

func NewService(orm *gorm.DB) albums_pb.AlbumServiceServer {
	return &service{
		orm: orm,
	}
}

func Published(q *query.Query) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		return db.Where(q.Album.Private.Is(false), q.Album.PublishedAt.Lte(time.Now()))
	}
}

func Paginate(q *query.Query, next *int32, pageSize *int32) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		if next != nil && *next != 0 {
			db = db.Where(q.Album.ID.Gt(*next))
		}
		s := 10
		if pageSize != nil {
			s = int(*pageSize)
		}
		return db.Limit(int(s))
	}
}

func (s *service) Index(ctx context.Context, r *albums_pb.IndexRequest) (*albums_pb.IndexResponse, error) {
	user := auth.GetUserClaims(ctx)

	qb := query.Use(s.orm).Album
	q := qb.WithContext(ctx).Order(qb.PublishedAt.Desc())

	if user == nil || (user != nil && !user.IsAdmin()) {
		q = q.Scopes(Published(query.Use(s.orm)))
	}

	if r.Next != nil && *r.Next != 0 {
		q.Where(qb.ID.Gt(*r.Next))
	}

	if r.Joins != nil && r.Joins.Categories {
		q = q.Preload(qb.Categories)
	}

	if r.Joins != nil && r.Joins.Medias {
		q = q.Preload(qb.Medias)
	}

	albums, err := q.Scopes(Paginate(query.Use(s.orm), r.Next, r.Limit)).Find()
	if err != nil {
		return nil, fmt.Errorf("unable list albums : %d %w", r.Next, err)
	}

	data := make([]*albums_pb.AlbumResponse, len(albums))
	for i, a := range albums {
		data[i] = transformAlbumFromDB(*a)
	}

	return &albums_pb.IndexResponse{
		Data: data,
		// Meta: api.Meta{Total: total, Limit: params.Limit},
	}, nil
}

// TODO bool include relation

func (s *service) GetBySlug(ctx context.Context, r *albums_pb.GetBySlugRequest) (*albums_pb.GetBySlugResponse, error) {
	user := auth.GetUserClaims(ctx)
	isAdmin := user != nil && user.IsAdmin()

	qb := query.Use(s.orm).Album

	query := qb.WithContext(ctx)

	if !isAdmin {
		query = query.Where(qb.PublishedAt.Lt(time.Now()), qb.Private.Is(false))
	}

	a, err := query.
		Preload(qb.Categories).
		Preload(qb.Medias).
		Where(qb.Slug.Eq(r.Slug)).
		First()

	if errors.Is(err, gorm.ErrRecordNotFound) {
		return nil, ErrNotFound
	}

	if err != nil {
		return nil, fmt.Errorf("error get album by slug: %w", err)
	}

	// TODO add medias and categes
	return &albums_pb.GetBySlugResponse{
		Album: transformAlbumFromDB(*a),
	}, err
}

func (s *service) Create(ctx context.Context, r *albums_pb.CreateRequest) (*albums_pb.CreateResponse, error) {
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

	// if r.Slug == nil {
	// 	s := slug.Make(r.Title)
	// 	r.Slug = &s
	// }

	var publishedAt *time.Time
	if r.PublishedAt != nil {
		t := r.PublishedAt.AsTime()
		publishedAt = &t
	}

	album := model.Album{
		Title:       r.Name,
		Slug:        r.Slug,
		SsoID:       &user.Sub,
		Body:        &r.Content,
		PublishedAt: publishedAt,
	}

	query := query.Use(s.orm).Album.WithContext(ctx)

	err := query.WithContext(ctx).Create(&album)
	// TODO Check duplicate
	if err != nil {
		return nil, err
	}

	data := transformAlbumFromDB(album)

	return &albums_pb.CreateResponse{
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

func (s *service) Update(ctx context.Context, r *albums_pb.UpdateRequest) (*albums_pb.UpdateResponse, error) {
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

	qb := query.Use(s.orm).Album

	query := qb.WithContext(ctx)

	update := map[string]interface{}{
		// "id":           r.Id,
		"private":      r.Private,
		"title":        r.Name,
		"slug":         r.Slug,
		"body":         r.Content,
		"published_at": r.PublishedAt,
	}
	_, err := query.Where(qb.ID.Eq(r.Id)).Updates(update)
	if err != nil {
		return nil, fmt.Errorf("error update album: %w", err)
	}
	query.Preload(qb.Categories).Preload(qb.Medias)

	a, err := query.Where(qb.ID.Eq(r.Id)).First()

	if errors.Is(err, gorm.ErrRecordNotFound) {
		return nil, ErrNotFound
	}

	data := transformAlbumFromDB(*a)

	return &albums_pb.UpdateResponse{
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

func (s *service) PatchAlbum(ctx context.Context, slug string, a AlbumUpdateRequest) (AlbumResponse, error) {
	return AlbumResponse{}, nil
}

func (s *service) Delete(ctx context.Context, r *albums_pb.DeleteRequest) (*albums_pb.DeleteResponse, error) {

	user := auth.GetUserClaims(ctx)
	if user == nil {
		return nil, ErrNoAuth
	}

	isAdmin := user != nil && user.IsAdmin()
	if !isAdmin {
		return nil, ErrNotAdmin
	}

	qb := query.Use(s.orm).Album

	ri, err := qb.WithContext(ctx).Where(qb.ID.Eq(r.Id)).Delete()
	if ri.RowsAffected == 0 {
		return nil, ErrNotFound
	}
	if err != nil {
		return nil, err
	}

	return nil, err
}
