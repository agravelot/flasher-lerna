package album_test

import (
	"context"
	"errors"
	"fmt"
	"log"
	"os"
	"strconv"
	"testing"
	"time"

	"api-go/config"
	"api-go/domain/album"
	albums_pb "api-go/gen/go/proto/albums/v2"
	"api-go/infrastructure/storage/postgres"
	"api-go/model"
	"api-go/pkg/auth"
	"api-go/query"

	"github.com/jackc/pgconn"
	"github.com/jackc/pgerrcode"
	"github.com/stretchr/testify/assert"
	"google.golang.org/protobuf/types/known/timestamppb"
)

var (
	db postgres.Postgres
)

var ssoId = "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"

// var token = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICIxamVHNzFZSHlUd25GUEVSb2NJeEVzS21lbjlWN1NjanRIZXFzak1KUXlZIn0.eyJleHAiOjE2MTExNjQ3MTAsImlhdCI6MTYxMTE2NDQxMCwiYXV0aF90aW1lIjoxNjExMTY0MzY0LCJqdGkiOiJlMThlMWNlOC05OTc5LTQ3NmQtOWYxMC1mOTk5OWJhMDQwZjgiLCJpc3MiOiJodHRwczovL2FjY291bnRzLmFncmF2ZWxvdC5ldS9hdXRoL3JlYWxtcy9hbnljbG91ZCIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiIzMDE1MWFlNS0yOGI0LTRjNmMtYjBhZS1lYTJlNmE0OWVmNjciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJmcm9udGVuZCIsIm5vbmNlIjoiMTkyOWEwZGEtMTU2ZS00NWZmLTgzM2YtYTU2MGIwNmI1YWNkIiwic2Vzc2lvbl9zdGF0ZSI6IjRlMWYxOWYzLTFhMmMtNGUxNS1iMWFhLTNlY2ZhMTkxMGRiOCIsImFjciI6IjAiLCJhbGxvd2VkLW9yaWdpbnMiOlsiaHR0cDovL2xvY2FsaG9zdDo4MDgwIiwiaHR0cDovL2xvY2FsaG9zdDo4MDgxIl0sInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgcHJvZmlsZSBlbWFpbCIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiJ0ZXN0IiwiZW1haWwiOiJ0ZXN0QHRlc3QuY29tIn0.PkfxSmIiG4lLE9hCjICcRPNpXC0X2QtVzYeUwAUwwe2G_6ArmMdZOkRVOKx3jiRO7PYu-D0NR9tAiv7yN9SDMDrIhtNoosgChB4PQ4wBf_YvHsJaAHwyK8Hu6h_8gxJIl3UYCKWTSYgLRK-IOE9E6FNlMdJK9UXAO_y2IBEZBO9QV-QxZH7SlYkm8VfoZzNzRMy82SgWLsQGDvwAAGCxHFRgTZdFNKPoqJylDyANBEuWanLwDohQKdNGqz6PlhtopmXo1v8kcHwBHxyMQ3mtRNCXBV6TOXo7oAWW3XeXGWjTtAiTY85Wr7R6IJ74WKpMrG-3PDL6Sx6n4JxOuurpLg"

func authAsUser(ctx context.Context) (context.Context, auth.Claims) {
	claims := auth.Claims{
		Exp:            1611164710,
		Iat:            1611164410,
		AuthTime:       1611164364,
		Jti:            "e18e1ce8-9979-476d-9f10-f9999ba040f8",
		Iss:            "https://accounts.example.com/auth/realms/test",
		Aud:            "account",
		Sub:            "30151ae5-28b4-4c6c-b0ae-ea2e6a49ef67",
		Typ:            "Bearer",
		Azp:            "frontend",
		Nonce:          "1929a0da-156e-45ff-833f-a560b06b5acd",
		SessionState:   "4e1f19f3-1a2c-4e15-b1aa-3ecfa1910db8",
		Acr:            "0",
		AllowedOrigins: []string{},
		RealmAccess: auth.RealmAccess{
			Roles: []string{},
		},
		ResourceAccess: auth.ResourceAccess{
			Account: auth.Account{
				Roles: []string{},
			},
		},
		Scope:             "openid profile email",
		EmailVerified:     true,
		PreferredUsername: "test",
		Email:             "test@test.com",
	}

	return context.WithValue(ctx, auth.UserClaimsKey, &claims), claims
}

func authAsAdmin(ctx context.Context) (context.Context, auth.Claims) {
	claims := auth.Claims{
		Exp:            1611164710,
		Iat:            1611164410,
		AuthTime:       1611164364,
		Jti:            "e18e1ce8-9979-476d-9f10-f9999ba040f8",
		Iss:            "https://accounts.example.com/auth/realms/test",
		Aud:            "account",
		Sub:            "30151ae5-28b4-4c6c-b0ae-ea2e6a49ef67",
		Typ:            "Bearer",
		Azp:            "frontend",
		Nonce:          "1929a0da-156e-45ff-833f-a560b06b5acd",
		SessionState:   "4e1f19f3-1a2c-4e15-b1aa-3ecfa1910db8",
		Acr:            "0",
		AllowedOrigins: []string{},
		RealmAccess: auth.RealmAccess{
			Roles: []string{"admin"},
		},
		ResourceAccess: auth.ResourceAccess{
			Account: auth.Account{
				Roles: []string{},
			},
		},
		Scope:             "openid profile email",
		EmailVerified:     true,
		PreferredUsername: "test",
		Email:             "test@test.com",
	}

	return context.WithValue(ctx, auth.UserClaimsKey, &claims), claims
}

func TestMain(m *testing.M) {
	config, err := config.FromDotEnv("../../.env")
	if err != nil {
		log.Fatal(fmt.Errorf("unable to load config: %w", err))
	}
	_db, err := postgres.New(config.Database.URI)
	if err != nil {
		log.Fatal(fmt.Errorf("unable to connect to the database: %w", err))
	}
	db = _db

	exitVal := m.Run() // Run tests
	// Do stuff after test

	os.Exit(exitVal)
}

// func setupTest() func() {
// 	// Setup here

// 	// Teardown here
// 	return func() {}
// }

/////// LIST ////////

func TestShouldBeAbleToListEmpty(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	r, err := s.Index(context.Background(), &albums_pb.IndexRequest{})

	assert.Nil(t, err)
	assert.Equal(t, 0, len(r.Data))
	// assert.Equal(t, int64(0), r.Meta.Total)
	// assert.Equal(t, int32(10), r.Meta.Limit)
}

func TestShouldBeAbleToListWithOnePublishedAlbum(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	sub10Min := time.Now().Add(-10 * time.Minute)
	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub10Min,
		Private:     false,
		SsoID:       &ssoId,
	}
	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(err)
	}

	r, _ := s.Index(context.Background(), &albums_pb.IndexRequest{})

	// assert.Equal(t, int64(1), r.Meta.Total)
	// assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeOrderedByDateOfPublication(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub100Min := time.Now().Add(-100 * time.Minute)
	sub10Min := time.Now().Add(-10 * time.Minute)
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album

	args := []model.Album{
		{
			Title:       "A good Title",
			Slug:        "a-good-title",
			PublishedAt: &sub100Min,
			Private:     false,
			SsoID:       &ssoId,
		},
		{
			Title:       "A good Title 2",
			Slug:        "a-good-title-2",
			PublishedAt: &sub10Min,
			Private:     false,
			SsoID:       &ssoId,
		},
		{
			Title:       "A good Title 3",
			Slug:        "a-good-title-3",
			PublishedAt: &sub5Min,
			Private:     false,
			SsoID:       &ssoId,
		},
	}

	for _, arg := range args {
		err = a.WithContext(context.Background()).Create(&arg)
		if err != nil {
			t.Error(fmt.Errorf("unable create album: %w", err))
		}
	}

	r, _ := s.Index(context.Background(), &albums_pb.IndexRequest{})

	// assert.Equal(t, int64(3), r.Meta.Total)
	// assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 3, len(r.Data))
	assert.Equal(t, "a-good-title-3", r.Data[0].Slug)
	assert.Equal(t, "a-good-title-2", r.Data[1].Slug)
	assert.Equal(t, "a-good-title", r.Data[2].Slug)
}

func TestShouldOnlyShowPublicAlbums(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub100Min := time.Now().Add(-100 * time.Minute)
	sub10Min := time.Now().Add(-10 * time.Minute)
	a := query.Use(tx.DB).Album

	args := []*model.Album{
		{
			Title:       "A good Title",
			Slug:        "a-good-title",
			PublishedAt: &sub100Min,
			Private:     false,
			SsoID:       &ssoId,
		},
		{
			Title:       "A good Title 2",
			Slug:        "a-good-title-2",
			PublishedAt: &sub10Min,
			Private:     true,
			SsoID:       &ssoId,
		},
		{
			Title:   "A good Title 3",
			Slug:    "a-good-title-3",
			Private: true,
			SsoID:   &ssoId,
		},
		{
			Title:   "A good Title 4",
			Slug:    "a-good-title-4",
			Private: false,
			SsoID:   &ssoId,
		},
	}

	err = a.WithContext(context.Background()).Create(args...)
	if err != nil {
		t.Error(fmt.Errorf("unable create album: %w", err))
	}

	r, _ := s.Index(context.Background(), &albums_pb.IndexRequest{})

	// assert.Equal(t, int64(1), r.Meta.Total)
	// assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, ssoId, r.Data[0].AuthorId)
}

func TestShouldBeAbleToListPublishedAlbumsOnSecondPage(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub100Min := time.Now().Add(-100 * time.Minute)
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album

	var lastPageID int32

	albums := []*model.Album{}

	for i := 0; i < 10; i++ {
		albums = append(albums, &model.Album{
			Title:       "A good Title " + strconv.Itoa(i),
			Slug:        "a-good-title-" + strconv.Itoa(i),
			PublishedAt: &sub100Min,
			Private:     false,
			SsoID:       &ssoId,
		})
	}

	albums = append(albums, &model.Album{
		Title:       "On second page",
		Slug:        "on-second-page",
		PublishedAt: &sub5Min,
		Private:     false,
		SsoID:       &ssoId,
	})

	err = a.WithContext(context.Background()).Create(albums...)
	if err != nil {
		t.Error(fmt.Errorf("unable create album: %w", err))
	}
	lastPageID = albums[9].ID
	limit := int32(10)

	r, _ := s.Index(context.Background(), &albums_pb.IndexRequest{Next: &lastPageID, Limit: &limit})

	// assert.Equal(t, int64(11), r.Meta.Total)
	// assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, albums[10].Title, r.Data[0].Title)
}

func TestShouldBeAbleToListPublishedAlbumsOnSecondPageWithCustomPerPage(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}

	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	var lastPageID int32
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album

	var albums []*model.Album
	for i := 0; i < 2; i++ {
		albums = append(albums, &model.Album{
			Title:       "On second page " + strconv.Itoa(i),
			Slug:        "on-second-page " + strconv.Itoa(i),
			PublishedAt: &sub5Min,
			Private:     false,
			SsoID:       &ssoId,
		})
	}

	albums = append(albums, &model.Album{
		Title:       "On second page",
		Slug:        "on-second-page",
		PublishedAt: &sub5Min,
		Private:     false,
		SsoID:       &ssoId,
	})

	err = a.WithContext(context.Background()).Create(albums...)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}
	lastPageID = albums[1].ID

	limit := int32(2)
	r, err := s.Index(context.Background(), &albums_pb.IndexRequest{Next: &lastPageID, Limit: &limit})
	if err != nil {
		t.Error(fmt.Errorf("Error listing albums: %w", err))
	}

	// assert.Equal(t, int64(3), r.Meta.Total)
	// assert.Equal(t, int32(2), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, albums[2].Title, r.Data[0].Title)
}

func TestShouldBeAbleToListNonPublishedAlbumAsAdmin(t *testing.T) {
	ctx, _ := authAsAdmin(context.Background())
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title: "On second page",
		Slug:  "on-second-page",
		SsoID: &ssoId,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, _ := s.Index(ctx, &albums_pb.IndexRequest{})

	// assert.Equal(t, int64(1), r.Meta.Total)
	// assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeAbleToListWithCustomPerPage(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()

	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title:       "On second page",
		Slug:        "on-second-page",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	limit := int32(15)
	r, _ := s.Index(context.Background(), &albums_pb.IndexRequest{Limit: &limit})

	// assert.Equal(t, int64(1), r.Meta.Total)
	// assert.Equal(t, int32(15), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldNotIncludeCategoriesByDefault(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album
	// c := query.Use(tx.DB).Category

	arg1 := model.Category{
		Name: "A good Category",
	}

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
		Categories:  []model.Category{arg1},
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.Index(context.Background(), &albums_pb.IndexRequest{})
	if err != nil {
		t.Error(err)
	}

	assert.Equal(t, 1, len(r.Data))
	assert.Nil(t, r.Data[0].Categories)
}

func TestShouldBeAbleToListWithCategories(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album
	// c := query.Use(tx.DB).Category

	arg1 := model.Category{
		Name: "A good Category",
	}

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
		Categories:  []model.Category{arg1},
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.Index(context.Background(), &albums_pb.IndexRequest{
		Joins: &albums_pb.IndexRequest_Joins{
			Categories: true,
		}})
	if err != nil {
		t.Error(err)
	}

	assert.Equal(t, 1, len(r.Data))
	assert.NotNil(t, r.Data[0].Categories)
	assert.Equal(t, 1, len(r.Data[0].Categories))
}

func TestShouldBeAbleToListWithMedias(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album
	m := query.Use(tx.DB).Medium

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	mimeType := "image/jpeg"
	arg1 := model.Medium{
		Name:             "A good Media",
		ModelID:          int64(arg.ID),
		Size:             int64(1),
		ModelType:        "App\\Models\\Album",
		CollectionName:   "albums",
		Disk:             "dummy",
		MimeType:         &mimeType,
		Manipulations:    `{"resize":{"width":100,"height":100}}`,
		CustomProperties: &model.CustomProperties{},
		ResponsiveImages: &model.ResponsiveImages{},
	}
	err = m.WithContext(context.Background()).Create(&arg1)
	if err != nil {
		t.Error(err)
		t.Error(fmt.Errorf("Error saving media for given album : %w", err))
	}

	r, err := s.Index(context.Background(), &albums_pb.IndexRequest{Joins: &albums_pb.IndexRequest_Joins{Medias: true}})
	if err != nil {
		t.Error(err)
	}

	assert.Equal(t, 1, len(r.Data))
	assert.NotNil(t, r.Data[0].Medias)
	assert.Equal(t, 1, len(r.Data[0].Medias))
	// assert.Equal(t, arg1.Name, (r.Data[0].Medias)[0].Name)
}

func TestShouldNotIncludeMediasByDefault(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album
	m := query.Use(tx.DB).Medium

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	mimeType := "image/jpeg"
	arg1 := model.Medium{
		Name:             "A good Media",
		ModelID:          int64(arg.ID),
		Size:             int64(1),
		ModelType:        "App\\Models\\Album",
		CollectionName:   "albums",
		Disk:             "dummy",
		MimeType:         &mimeType,
		Manipulations:    `{"resize":{"width":100,"height":100}}`,
		CustomProperties: &model.CustomProperties{},
		ResponsiveImages: &model.ResponsiveImages{},
	}
	err = m.WithContext(context.Background()).Create(&arg1)
	if err != nil {
		t.Error(err)
		t.Error(fmt.Errorf("Error saving media for given album : %w", err))
	}

	r, err := s.Index(context.Background(), &albums_pb.IndexRequest{})
	if err != nil {
		t.Error(err)
	}

	assert.Equal(t, 1, len(r.Data))
	assert.Nil(t, r.Data[0].Medias)
}

func TestShouldNotListNonPublishedAlbums(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title:   "A good Title",
		SsoID:   &ssoId,
		Private: false,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err2 := s.Index(context.Background(), &albums_pb.IndexRequest{})
	if err2 != nil {
		t.Error(err)
	}

	// assert.Equal(t, int64(0), r.Meta.Total)
	// assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 0, len(r.Data))
}

///////// SHOW  ///////////

func TestShouldBeAbleToGetPublishedAlbumAsGuest(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetBySlug(context.Background(), &albums_pb.GetBySlugRequest{Slug: arg.Slug})

	assert.NoError(t, err)
	assert.Equal(t, arg.Slug, r.Album.Slug)
}

func TestShouldBeAbleToGetPublishedAlbumAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsUser(context.Background())
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetBySlug(ctx, &albums_pb.GetBySlugRequest{Slug: arg.Slug})

	assert.NoError(t, err)
	assert.Equal(t, arg.Slug, r.Album.Slug)
}

func TestShouldNotBeAbleToGetNonPublishedAlbumAsGuest(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	arg := model.Album{
		Title: "A good Title",
		SsoID: &ssoId,
		Slug:  slug,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	_, err = s.GetBySlug(context.Background(), &albums_pb.GetBySlugRequest{Slug: slug})

	assert.Error(t, err)
	assert.Equal(t, album.ErrNotFound, err)
}

func TestShouldNotBeAbleToGetNonPublishedAlbumAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsUser(context.Background())
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title: "A good Title",
		SsoID: &ssoId,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	_, err = s.GetBySlug(ctx, &albums_pb.GetBySlugRequest{Slug: arg.Slug})

	assert.Error(t, err)
	assert.Equal(t, album.ErrNotFound, err)
}

func TestShouldBeAbleToGetNonPublishedAlbumAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title: "A good Title",
		SsoID: &ssoId,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetBySlug(ctx, &albums_pb.GetBySlugRequest{Slug: arg.Slug})

	assert.NoError(t, err)
	assert.Equal(t, arg.Slug, r.Album.Slug)
}

// ///////// POST  ///////////

func TestShouldBeAbleToCreateAnAlbumAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, claims := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	arg := &albums_pb.CreateRequest{
		Name:            "A good Title",
		MetaDescription: "meta",
		Slug:            "a-good-title",
	}
	res, err := s.Create(ctx, arg)

	assert.NoError(t, err)
	total, err := a.WithContext(context.Background()).Count()
	if err != nil {
		t.Error(fmt.Errorf("Error counting albums: %w", err))
	}
	assert.Equal(t, 1, int(total))
	assert.Equal(t, arg.Name, res.Title)
	assert.Equal(t, "a-good-title", res.Slug)
	assert.Equal(t, claims.Sub, res.AuthorId)
	assert.Equal(t, arg.PublishedAt, res.PublishedAt)
}

func TestShouldBeAbleToCreateAnPublishedAlbumAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, claims := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute).UTC()
	arg := albums_pb.CreateRequest{
		Name:            "A good Title",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	res, err := s.Create(ctx, &arg)

	assert.NoError(t, err)
	total, err := a.WithContext(context.Background()).Count()

	if err != nil {
		t.Error(fmt.Errorf("Error counting albums: %w", err))
	}
	assert.Equal(t, 1, int(total))
	assert.Equal(t, arg.Name, res.Title)
	assert.Equal(t, "a-good-title", res.Slug)
	assert.Equal(t, claims.Sub, res.AuthorId)
	assert.Equal(t, arg.PublishedAt.Seconds, res.PublishedAt.Seconds)
}

func TestShouldNotBeAbleToCreateAnAlbumWithSameSlug(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "A good Title",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)
	assert.NoError(t, err)

	tx.DB.SavePoint("beforeCreateDuplicate")
	_, err = s.Create(ctx, &arg)
	tx.DB.RollbackTo("beforeCreateDuplicate")

	var pgErr *pgconn.PgError
	assert.Error(t, err)
	assert.True(t, errors.As(err, &pgErr))
	assert.Equal(t, pgerrcode.UniqueViolation, pgErr.Code)

	total, err := a.WithContext(context.Background()).Count()
	assert.NoError(t, err)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithEmptyTitle(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)

	assert.Error(t, err)
	firstValidationError := err.(albums_pb.CreateRequestMultiError)[0].(albums_pb.CreateRequestValidationError)
	assert.Equal(t, "Name", firstValidationError.Field())
	total, err := a.WithContext(context.Background()).Count()
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithTooLongTitle(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "a very too long big endb.DBous title that will never fit in any screen...",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)

	assert.Error(t, err)
	firstValidationError := err.(albums_pb.CreateRequestMultiError)[0].(albums_pb.CreateRequestValidationError)
	assert.Equal(t, "Name", firstValidationError.Field())
	total, err := a.WithContext(context.Background()).Count()
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithEmptyMetaDescription(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "a good title",
		MetaDescription: "",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)

	assert.Error(t, err)
	firstValidationError := err.(albums_pb.CreateRequestMultiError)[0].(albums_pb.CreateRequestValidationError)
	assert.Equal(t, "MetaDescription", firstValidationError.Field())
	total, err := a.WithContext(context.Background()).Count()
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithTooLongMetaDescription(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "a good title",
		MetaDescription: "a very too long big endb.DBous meta description that will never fit in any screen..a very too long big endb.DBous meta description that will never fit in any screen..a very too long big endb.DBous meta description that will never fit in any screen..a very too long big endb.DBous meta description that will never fit in any screen..a very too long big endb.DBous meta description that will never fit in any screen...",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)

	assert.Error(t, err)
	firstValidationError := err.(albums_pb.CreateRequestMultiError)[0].(albums_pb.CreateRequestValidationError)
	assert.Equal(t, "MetaDescription", firstValidationError.Field())
	total, err := a.WithContext(context.Background()).Count()
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToCreateAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsUser(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "a good title",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)

	assert.Error(t, err)
	assert.Equal(t, album.ErrNotAdmin, err)
	total, err := a.WithContext(context.Background()).Count()

	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToCreateAsGuest(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "a good title",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(context.Background(), &arg)

	assert.Error(t, err)
	assert.Equal(t, album.ErrNoAuth, err)
	total, err := a.WithContext(context.Background()).Count() // TODO Add filter published to pass test
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

// //////// UPDATE //////////

func TestShouldBeAbleToUpdateAlbumTitleAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	err = tx.DB.Create(&a).Error
	assert.NoError(t, err)

	expectedTitle := "A new Title"
	new, err := s.Update(ctx, &albums_pb.UpdateRequest{
		Id:              a.ID,
		Name:            expectedTitle,
		Slug:            a.Slug,
		MetaDescription: a.MetaDescription,
	})

	assert.NoError(t, err)
	assert.Equal(t, expectedTitle, new.Title)
	var total int64
	tx.DB.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateAlbumTooShortTitleAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	tx.DB.Create(&a)

	_, err = s.Update(ctx, &albums_pb.UpdateRequest{
		Id:              a.ID,
		Slug:            a.Slug,
		Name:            "",
		MetaDescription: a.MetaDescription,
	})

	assert.Error(t, err)
	assert.IsType(t, albums_pb.UpdateRequestMultiError{}, err)
	firstValidationError := err.(albums_pb.UpdateRequestMultiError)[0].(albums_pb.UpdateRequestValidationError)
	assert.Equal(t, "Name", firstValidationError.Field())
	var total int64
	tx.DB.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldBeAbleToUpdateAlbumToAsPublishedAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
		Private:         true,
	}
	tx.DB.Create(&a)

	expectedTitle := "A new Title"
	new, err := s.Update(ctx, &albums_pb.UpdateRequest{
		Id:              a.ID,
		Name:            expectedTitle,
		Slug:            a.Slug,
		MetaDescription: a.MetaDescription,
		Private:         false,
		PublishedAt:     nil,
	})

	assert.NoError(t, err)
	assert.Equal(t, expectedTitle, new.Title)
	var expectedAlbum model.Album
	tx.DB.Model(&model.Album{}).Find(&expectedAlbum, a.ID)
	assert.Equal(t, a.ID, expectedAlbum.ID)
	assert.Equal(t, expectedTitle, expectedAlbum.Title)
	assert.Equal(t, false, expectedAlbum.Private)
	assert.Nil(t, expectedAlbum.PublishedAt)
}

func TestShouldNotBeAbleToUpdateAlbumAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsUser(context.Background())

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	tx.DB.Create(&a)

	_, err = s.Update(ctx, &albums_pb.UpdateRequest{
		Id:   a.ID,
		Name: "A new Title",
	})

	assert.Error(t, err)
	assert.Equal(t, album.ErrNotAdmin, err)
	var total int64
	tx.DB.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateAlbumAsGuest(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	tx.DB.Create(&a)

	_, err = s.Update(context.Background(), &albums_pb.UpdateRequest{
		Id:   a.ID,
		Name: "A new Title",
	})

	assert.Error(t, err)
	assert.Equal(t, album.ErrNoAuth, err)
	var total int64
	tx.DB.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

// //////// DELETE //////////

func TestAdminShouldBeAbleToDeleteAndNotSoftDeleted(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	_, err = s.Delete(ctx, &albums_pb.DeleteRequest{Id: a.ID})
	assert.NoError(t, err)

	var total, totalScopeless int64
	tx.DB.Model(&a).Count(&total)
	tx.DB.Model(&a).Unscoped().Count(&totalScopeless)
	assert.Equal(t, 0, int(total))
	assert.Equal(t, 0, int(totalScopeless))
}

func TestAdminShouldNotBeAbleToDeleteAnNonExistantAlbum(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())

	_, err = s.Delete(ctx, &albums_pb.DeleteRequest{Id: 1})

	assert.Error(t, err)
	assert.ErrorIs(t, err, album.ErrNotFound)
}

func TestUserShouldNotBeAbleToDelete(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsUser(context.Background())
	slug := "a-good-slug"
	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            slug,
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	_, err = s.Delete(ctx, &albums_pb.DeleteRequest{Id: a.ID})

	assert.Error(t, err)
	assert.EqualError(t, err, album.ErrNotAdmin.Error())
}

func TestGuestShouldNotBeAbleToDelete(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	_, err = s.Delete(context.Background(), &albums_pb.DeleteRequest{Id: 1})

	assert.Error(t, err)
	assert.EqualError(t, err, album.ErrNoAuth.Error())
}
