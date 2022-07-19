package album

import (
	"api-go/model"
	"api-go/pkg/auth"
	"api-go/query"
	"api-go/storage/postgres"
	"context"
	"errors"
	"fmt"
	"time"

	albumspb "api-go/gen/go/proto/albums/v2"
	categoriespb "api-go/gen/go/proto/categories/v2"
	mediaspb "api-go/gen/go/proto/medias/v2"

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
	albumspb.AlbumServiceServer
	// TODO Use storage.Storage interface
	storage *postgres.Postgres
}

func NewService(orm *postgres.Postgres) albumspb.AlbumServiceServer {
	return &service{
		storage: orm,
	}
}

func published(q *query.Query) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		return db.Where(q.Album.Private.Is(false), q.Album.PublishedAt.Lte(time.Now()))
	}
}

func paginate(q *query.Query, next *int32, pageSize *int32) func(db gen.Dao) gen.Dao {
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

func (s *service) Index(ctx context.Context, r *albumspb.IndexRequest) (*albumspb.IndexResponse, error) {
	user := auth.GetUserClaims(ctx)

	qb := query.Use(s.storage.DB).Album
	q := qb.WithContext(ctx).Order(qb.PublishedAt.Desc())

	if user == nil || (user != nil && !user.IsAdmin()) {
		q = q.Scopes(published(query.Use(s.storage.DB)))
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

	albums, err := q.Scopes(paginate(query.Use(s.storage.DB), r.Next, r.Limit)).Find()
	if err != nil {
		return nil, fmt.Errorf("unable list albums : %d %w", r.Next, err)
	}

	data := make([]*albumspb.AlbumResponse, 0, len(albums))
	for _, a := range albums {
		data = append(data, transformAlbumFromDB(*a))
	}

	return &albumspb.IndexResponse{
		Data: data,
		// Meta: api.Meta{Total: total, Limit: params.Limit},
	}, nil
}

// TODO bool include relation

func (s *service) GetBySlug(ctx context.Context, r *albumspb.GetBySlugRequest) (*albumspb.GetBySlugResponse, error) {
	user := auth.GetUserClaims(ctx)
	isAdmin := user != nil && user.IsAdmin()

	qb := query.Use(s.storage.DB).Album

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

	query := query.Use(s.storage.DB).Album.WithContext(ctx)

	err := query.WithContext(ctx).Create(&album)
	// TODO Check duplicate
	if err != nil {
		return nil, err
	}

	data := transformAlbumFromDB(album)

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

	qb := query.Use(s.storage.DB).Album

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
