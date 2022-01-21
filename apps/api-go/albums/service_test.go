package album

import (
	"api-go/auth"
	"api-go/config"
	"api-go/database2"
	"api-go/tutorial"
	"context"
	"database/sql"
	"errors"
	"fmt"
	"os"
	"strconv"
	"testing"
	"time"

	"github.com/go-playground/validator/v10"
	"github.com/google/uuid"
	"github.com/jackc/pgconn"
	"github.com/jackc/pgerrcode"
	"github.com/jackc/pgtype"
	"github.com/stretchr/testify/assert"
)

var (
	s  Service
	db *tutorial.Queries
)

var token = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICIxamVHNzFZSHlUd25GUEVSb2NJeEVzS21lbjlWN1NjanRIZXFzak1KUXlZIn0.eyJleHAiOjE2MTExNjQ3MTAsImlhdCI6MTYxMTE2NDQxMCwiYXV0aF90aW1lIjoxNjExMTY0MzY0LCJqdGkiOiJlMThlMWNlOC05OTc5LTQ3NmQtOWYxMC1mOTk5OWJhMDQwZjgiLCJpc3MiOiJodHRwczovL2FjY291bnRzLmFncmF2ZWxvdC5ldS9hdXRoL3JlYWxtcy9hbnljbG91ZCIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiIzMDE1MWFlNS0yOGI0LTRjNmMtYjBhZS1lYTJlNmE0OWVmNjciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJmcm9udGVuZCIsIm5vbmNlIjoiMTkyOWEwZGEtMTU2ZS00NWZmLTgzM2YtYTU2MGIwNmI1YWNkIiwic2Vzc2lvbl9zdGF0ZSI6IjRlMWYxOWYzLTFhMmMtNGUxNS1iMWFhLTNlY2ZhMTkxMGRiOCIsImFjciI6IjAiLCJhbGxvd2VkLW9yaWdpbnMiOlsiaHR0cDovL2xvY2FsaG9zdDo4MDgwIiwiaHR0cDovL2xvY2FsaG9zdDo4MDgxIl0sInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgcHJvZmlsZSBlbWFpbCIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiJ0ZXN0IiwiZW1haWwiOiJ0ZXN0QHRlc3QuY29tIn0.PkfxSmIiG4lLE9hCjICcRPNpXC0X2QtVzYeUwAUwwe2G_6ArmMdZOkRVOKx3jiRO7PYu-D0NR9tAiv7yN9SDMDrIhtNoosgChB4PQ4wBf_YvHsJaAHwyK8Hu6h_8gxJIl3UYCKWTSYgLRK-IOE9E6FNlMdJK9UXAO_y2IBEZBO9QV-QxZH7SlYkm8VfoZzNzRMy82SgWLsQGDvwAAGCxHFRgTZdFNKPoqJylDyANBEuWanLwDohQKdNGqz6PlhtopmXo1v8kcHwBHxyMQ3mtRNCXBV6TOXo7oAWW3XeXGWjTtAiTY85Wr7R6IJ74WKpMrG-3PDL6Sx6n4JxOuurpLg"

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

	return context.WithValue(ctx, "user", &claims), claims
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

	return context.WithValue(ctx, "user", &claims), claims
}

func TestMain(m *testing.M) {
	config := config.LoadDotEnv("../")
	db, _ = database2.Init(config)
	database2.ClearDB(db)
	// db.AutoMigrate(&MediaModel{})
	// db.AutoMigrate(&AlbumModel{})
	// db.AutoMigrate(&CategoryModel{})
	// db.AutoMigrate(&AlbumCategoryModel{})

	// db.AutoMigrate(&MediaModel{})
	// db.AutoMigrate(&AlbumModel{})
	// db.AutoMigrate(&CategoryModel{})
	// db.AutoMigrate(&AlbumCategoryModel{})
	// err := db.SetupJoinTable(&AlbumModel{}, "Categories", &AlbumCategoryModel{})
	// if err != nil {
	// 	panic(err)
	// }
	s = NewService(db)

	exitVal := m.Run() // Run tests
	// Do stuff after test

	os.Exit(exitVal)
}

/////// LIST ////////

func TestShouldBeAbleToListEmpty(t *testing.T) {
	database2.ClearDB(db)
	r, err := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 10}})

	assert.Nil(t, err)
	assert.Equal(t, 0, len(r.Data))
	assert.Equal(t, int64(0), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
}

func TestShouldBeAbleToListWithOnePublishedAlbum(t *testing.T) {
	database2.ClearDB(db)

	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title:       "A good Title aze",
		PublishedAt: sql.NullTime{Time: time.Now().Add(-10 * time.Minute), Valid: true},
		Private:     false,
		SsoID:       uuid.NullUUID{UUID: id, Valid: true},
	}
	_, err = db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(err)
	}

	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 10}})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeOrderedByDateOfPublication(t *testing.T) {
	database2.ClearDB(db)
	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}
	args := []tutorial.CreateAlbumParams{
		{
			Title:       "A good Title",
			Slug:        "a-good-title",
			PublishedAt: sql.NullTime{Time: time.Now().Add(-100 * time.Minute), Valid: true},
			Private:     false,
			SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		},
		{
			Title:       "A good Title 2",
			Slug:        "a-good-title-2",
			PublishedAt: sql.NullTime{Time: time.Now().Add(-10 * time.Minute), Valid: true},
			Private:     false,
			SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		},
		{
			Title:       "A good Title 3",
			Slug:        "a-good-title-3",
			PublishedAt: sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
			Private:     false,
			SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		},
	}

	for _, arg := range args {
		_, err := db.CreateAlbum(context.Background(), arg)
		if err != nil {
			t.Error(fmt.Errorf("Error creating album: %w", err))
		}
	}

	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 10}})

	assert.Equal(t, int64(3), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 3, len(r.Data))
	assert.Equal(t, "a-good-title-3", r.Data[0].Slug)
	assert.Equal(t, "a-good-title-2", r.Data[1].Slug)
	assert.Equal(t, "a-good-title", r.Data[2].Slug)
}

func TestShouldOnlyShowPublicAlbums(t *testing.T) {
	database2.ClearDB(db)

	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}
	args := []tutorial.CreateAlbumParams{
		{
			Title:       "A good Title",
			Slug:        "a-good-title",
			PublishedAt: sql.NullTime{Time: time.Now().Add(-100 * time.Minute), Valid: true},
			Private:     false,
			SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		},
		{
			Title:       "A good Title 2",
			Slug:        "a-good-title-2",
			PublishedAt: sql.NullTime{Time: time.Now().Add(-10 * time.Minute), Valid: true},
			Private:     true,
			SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		},
		{
			Title:   "A good Title 3",
			Slug:    "a-good-title-3",
			Private: true,
			SsoID:   uuid.NullUUID{UUID: id, Valid: true},
		},
		{
			Title:   "A good Title 4",
			Slug:    "a-good-title-4",
			Private: false,
			SsoID:   uuid.NullUUID{UUID: id, Valid: true},
		},
	}

	for _, arg := range args {
		_, err := db.CreateAlbum(context.Background(), arg)
		if err != nil {
			t.Error(fmt.Errorf("Error creating album: %w", err))
		}
	}

	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 10}})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeAbleToListPublishedAlbumsOnSecondPage(t *testing.T) {
	database2.ClearDB(db)

	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	var lastPageID int32

	for i := 0; i < 10; i++ {
		arg := tutorial.CreateAlbumParams{
			Title:       "A good Title " + strconv.Itoa(i),
			Slug:        "a-good-title-" + strconv.Itoa(i),
			PublishedAt: sql.NullTime{Time: time.Now().Add(-100 * time.Minute), Valid: true},
			Private:     false,
			SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		}

		a, err := db.CreateAlbum(context.Background(), arg)
		if i == 9 {
			lastPageID = a.ID
		}
		if err != nil {
			t.Error(fmt.Errorf("Error creating album: %w", err))
		}
	}

	arg := tutorial.CreateAlbumParams{
		Title:       "On second page",
		Slug:        "on-second-page",
		PublishedAt: sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
		Private:     false,
		SsoID:       uuid.NullUUID{UUID: id, Valid: true},
	}

	_, err = db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{Next: uint(lastPageID), Limit: 10}})

	assert.Equal(t, int64(11), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, arg.Title, r.Data[0].Title)
}

func TestShouldBeAbleToListPublishedAlbumsOnSecondPageWithCustomPerPage(t *testing.T) {
	database2.ClearDB(db)

	var albums []tutorial.Album
	var lastPageID int32

	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	for i := 0; i < 2; i++ {
		// tmp := AlbumModel{Title: "A good Title " + strconv.Itoa(i), PublishedAt: null.NewTime(time.Now().Add(-5*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
		// db.Create(&tmp)

		arg := tutorial.CreateAlbumParams{
			Title:       "On second page " + strconv.Itoa(i),
			Slug:        "on-second-page " + strconv.Itoa(i),
			PublishedAt: sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
			Private:     false,
			SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		}

		a, err := db.CreateAlbum(context.Background(), arg)
		if err != nil {
			t.Error(fmt.Errorf("Error creating album: %w", err))
		}

		if i == 1 {
			lastPageID = a.ID
		}

		albums = append(albums, a)

	}
	// a := AlbumModel{Title: "On second page", PublishedAt: null.NewTime(time.Now().Add(-5*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
	// db.Create(&a)

	arg := tutorial.CreateAlbumParams{
		Title:       "On second page",
		Slug:        "on-second-page",
		PublishedAt: sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
		Private:     false,
		SsoID:       uuid.NullUUID{UUID: id, Valid: true},
	}

	a, err := db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{Next: uint(lastPageID), Limit: 2}})

	assert.Equal(t, int64(3), r.Meta.Total)
	assert.Equal(t, int32(2), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, a.Title, r.Data[0].Title)
}

func TestShouldBeAbleToListNonPublishedAlbumAsAdmin(t *testing.T) {
	ctx, _ := authAsAdmin(context.Background())
	database2.ClearDB(db)

	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title: "On second page",
		Slug:  "on-second-page",
		SsoID: uuid.NullUUID{UUID: id, Valid: true},
	}

	_, err = db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, _ := s.GetAlbumList(ctx, AlbumListParams{PaginationParams: PaginationParams{0, 10}})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeAbleToListWithCustomPerPage(t *testing.T) {
	database2.ClearDB(db)

	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title:       "On second page",
		Slug:        "on-second-page",
		PublishedAt: sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
		SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		Private:     false,
	}

	_, err = db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 15}})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(15), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeAbleToListWithCategories(t *testing.T) {
	database2.ClearDB(db)

	arg1 := tutorial.CreateCategoryParams{
		Name: "A good Category",
	}
	c, err := db.CreateCategory(context.Background(), arg1)

	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title:       "A good Title",
		PublishedAt: sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
		SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		Private:     false,
		// Categories:  &categories,
	}

	a, err := db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	err = db.LinkCategoryToAlbum(context.Background(), tutorial.LinkCategoryToAlbumParams{
		AlbumID:    a.ID,
		CategoryID: c.ID,
	})
	if err != nil {
		t.Error(fmt.Errorf("Error linking album with category : %w", err))
	}

	r, err := s.GetAlbumList(context.Background(), AlbumListParams{Joins: AlbumListJoinsParams{Categories: true}, PaginationParams: PaginationParams{0, 10}})
	if err != nil {
		t.Error(err)
	}

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, 1, len(*r.Data[0].Categories))
}

func TestShouldBeAbleToListWithoutCategories(t *testing.T) {
	database2.ClearDB(db)

	arg1 := tutorial.CreateCategoryParams{
		Name: "A good Category",
	}
	c, err := db.CreateCategory(context.Background(), arg1)

	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title:       "A good Title",
		PublishedAt: sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
		SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		Private:     false,
		// Categories:  &categories,
	}

	a, err := db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	err = db.LinkCategoryToAlbum(context.Background(), tutorial.LinkCategoryToAlbumParams{
		AlbumID:    a.ID,
		CategoryID: c.ID,
	})
	if err != nil {
		t.Error(fmt.Errorf("Error linking album with category : %w", err))
	}

	r, err := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 10}})
	if err != nil {
		t.Error(err)
	}

	var nilCategories *[]CategoryReponse

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, nilCategories, r.Data[0].Categories)
}

func TestShouldBeAbleToListWithMedias(t *testing.T) {
	database2.ClearDB(db)

	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title:       "A good Title",
		PublishedAt: sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
		SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		Private:     false,
	}

	a, err := db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	arg1 := tutorial.CreateMediaParams{
		Name:             "A good Media",
		ModelID:          int64(a.ID),
		Size:             int64(1),
		ModelType:        "App\\Models\\Album",
		CollectionName:   "albums",
		Disk:             "dummy",
		MimeType:         sql.NullString{String: "image/jpeg", Valid: true},
		Manipulations:    pgtype.JSON{Bytes: []byte(`{"resize":{"width":100,"height":100}}`), Status: pgtype.Present},
		CustomProperties: pgtype.JSON{Bytes: []byte(`{}`), Status: pgtype.Present},
		ResponsiveImages: pgtype.JSON{Bytes: []byte(`[]`), Status: pgtype.Present},
	}
	m, err := db.CreateMedia(context.Background(), arg1)
	if err != nil {
		t.Error(err)
		t.Error(fmt.Errorf("Error saving media for given album : %w", err))
	}

	r, err := s.GetAlbumList(context.Background(), AlbumListParams{Joins: AlbumListJoinsParams{Medias: true}, PaginationParams: PaginationParams{0, 10}})
	if err != nil {
		t.Error(err)
	}

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.NotNil(t, r.Data[0].Medias)
	assert.Equal(t, 1, len(*r.Data[0].Medias))
	assert.Equal(t, m.Name, (*r.Data[0].Medias)[0].Name)
}

// func TestShouldBeAbleToListWithMedias(t *testing.T) {
// 	database2.ClearDB(db)
// 	medias := []MediaModel{
// 		{Name: "A good Media"},
// 	}
// 	a := AlbumModel{
// 		Title:       "A good Title",
// 		PublishedAt: null.NewTime(time.Now().Add(-5*time.Minute), true),
// 		SsoID:       "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11",
// 		Private:     boolPtr(false),
// 		Medias:      &medias,
// 	}
// 	err := db.Create(&a).Error
// 	if err != nil {
// 		t.Error(err)
// 	}

// 	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{Joins: AlbumListJoinsParams{Medias: true}, PaginationParams: PaginationParams{0, 10}})

// 	assert.Equal(t, int64(1), r.Meta.Total)
// 	assert.Equal(t, 10, r.Meta.Limit)
// 	assert.Equal(t, 1, len(r.Data))
// 	assert.Equal(t, 1, len(*r.Data[0].Medias))
// }

// func TestShouldBeAbleToListWithoutMedias(t *testing.T) {
// 	database2.ClearDB(db)
// 	medias := []MediaModel{
// 		{Name: "A good Media"},
// 	}
// 	a := AlbumModel{
// 		Title:       "A good Title",
// 		PublishedAt: null.NewTime(time.Now().Add(-5*time.Minute), true),
// 		SsoID:       "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11",
// 		Private:     boolPtr(false),
// 		Medias:      &medias,
// 	}
// 	err := db.Create(&a).Error
// 	if err != nil {
// 		t.Error(err)
// 	}

// 	r, err := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 10}})
// 	if err != nil {
// 		t.Error(err)
// 	}

// 	var nilMedias *[]MediaModel

// 	assert.Equal(t, int64(1), r.Meta.Total)
// 	assert.Equal(t, 10, r.Meta.Limit)
// 	assert.Equal(t, 1, len(r.Data))
// 	assert.Equal(t, nilMedias, r.Data[0].Medias)
// }

func TestShouldNotListNonPublishedAlbums(t *testing.T) {
	database2.ClearDB(db)
	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title:   "A good Title",
		SsoID:   uuid.NullUUID{UUID: id, Valid: true},
		Private: false,
	}

	_, err = db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err2 := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 10}})
	if err2 != nil {
		t.Error(err)
	}

	assert.Equal(t, int64(0), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 0, len(r.Data))
}

///////// SHOW  ///////////

func TestShouldBeAbleToGetPublishedAlbumAsGuest(t *testing.T) {
	database2.ClearDB(db)
	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title:       "A good Title",
		PublishedAt: sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
		SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		Private:     false,
	}

	a, err := db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetAlbum(context.Background(), a.Slug)

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

func TestShouldBeAbleToGetPublishedAlbumAsUser(t *testing.T) {
	database2.ClearDB(db)
	ctx, _ := authAsUser(context.Background())
	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title:       "A good Title",
		PublishedAt: sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
		SsoID:       uuid.NullUUID{UUID: id, Valid: true},
		Private:     false,
	}

	a, err := db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetAlbum(ctx, a.Slug)

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

func TestShouldNotBeAbleToGetNonPublishedAlbumAsGuest(t *testing.T) {
	database2.ClearDB(db)
	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title: "A good Title",
		SsoID: uuid.NullUUID{UUID: id, Valid: true},
	}

	a, err := db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	_, err = s.GetAlbum(context.Background(), a.Slug)

	assert.Error(t, err)
	assert.Equal(t, ErrNotFound, err)
}

func TestShouldNotBeAbleToGetNonPublishedAlbumAsUser(t *testing.T) {
	database2.ClearDB(db)
	ctx, _ := authAsUser(context.Background())

	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title: "A good Title",
		SsoID: uuid.NullUUID{UUID: id, Valid: true},
	}

	a, err := db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	_, err = s.GetAlbum(ctx, a.Slug)

	assert.Error(t, err)
	assert.Equal(t, ErrNotFound, err)
}

func TestShouldBeAbleToGetNonPublishedAlbumAsAdmin(t *testing.T) {
	database2.ClearDB(db)
	ctx, _ := authAsAdmin(context.Background())

	id, err := uuid.Parse("a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11")
	if err != nil {
		t.Error(err)
	}

	arg := tutorial.CreateAlbumParams{
		Title: "A good Title",
		SsoID: uuid.NullUUID{UUID: id, Valid: true},
	}

	a, err := db.CreateAlbum(context.Background(), arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetAlbum(ctx, a.Slug)

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

// ///////// POST  ///////////

func TestShouldBeAbleToCreateAnAlbumAsAdmin(t *testing.T) {
	database2.ClearDB(db)
	ctx, claims := authAsAdmin(context.Background())

	id, err := uuid.Parse(claims.Sub)
	if err != nil {
		t.Error(err)
	}

	arg := AlbumRequest{
		Title:           "A good Title",
		MetaDescription: "meta",
		Slug:            "a-good-title",
	}

	res, err := s.PostAlbum(ctx, arg)

	assert.NoError(t, err)
	total, err := db.CountAlbums(ctx, true)
	if err != nil {
		t.Error(fmt.Errorf("Error counting albums: %w", err))
	}
	assert.Equal(t, 1, int(total))
	assert.Equal(t, arg.Title, res.Title)
	assert.Equal(t, "a-good-title", res.Slug)
	assert.Equal(t, uuid.NullUUID{UUID: id, Valid: true}, res.SsoID)
	assert.False(t, res.PublishedAt.Valid)
}

func TestShouldBeAbleToCreateAnPublishedAlbumAsAdmin(t *testing.T) {
	database2.ClearDB(db)
	ctx, claims := authAsAdmin(context.Background())

	id, err := uuid.Parse(claims.Sub)
	if err != nil {
		t.Error(err)
	}

	arg := AlbumRequest{
		Title:           "A good Title",
		MetaDescription: "meta",
		Slug:            "a-good-title",
		PublishedAt:     sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
	}

	res, err := s.PostAlbum(ctx, arg)

	assert.NoError(t, err)
	total, err := db.CountAlbums(ctx, true)
	if err != nil {
		t.Error(fmt.Errorf("Error counting albums: %w", err))
	}
	assert.Equal(t, 1, int(total))
	assert.Equal(t, arg.Title, res.Title)
	assert.Equal(t, "a-good-title", res.Slug)
	assert.Equal(t, uuid.NullUUID{UUID: id, Valid: true}, res.SsoID)
	assert.True(t, res.PublishedAt.Valid)
}

func TestShouldNotBeAbleToCreateAnAlbumWithSameSlug(t *testing.T) {
	database2.ClearDB(db)
	ctx, _ := authAsAdmin(context.Background())

	arg := AlbumRequest{
		Title:           "A good Title",
		MetaDescription: "meta",
		Slug:            "a-good-title",
		PublishedAt:     sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
	}

	_, err := s.PostAlbum(ctx, arg)
	assert.NoError(t, err)

	_, err = s.PostAlbum(ctx, arg)

	var pgErr *pgconn.PgError
	assert.Error(t, err)
	assert.True(t, errors.As(err, &pgErr))
	assert.Equal(t, pgerrcode.UniqueViolation, pgErr.Code)

	total, err := db.CountAlbums(ctx, true)
	assert.NoError(t, err)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithEmptyTitle(t *testing.T) {
	database2.ClearDB(db)
	ctx, _ := authAsAdmin(context.Background())

	arg := AlbumRequest{
		Title:           "",
		MetaDescription: "meta",
		Slug:            "a-good-title",
		PublishedAt:     sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
	}

	_, err := s.PostAlbum(ctx, arg)

	assert.Error(t, err)
	assert.Equal(t, "Key: 'AlbumRequest.Title' Error:Field validation for 'Title' failed on the 'required' tag", err.Error())

	total, err := db.CountAlbums(ctx, true)
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithTooLongTitle(t *testing.T) {
	database2.ClearDB(db)
	ctx, _ := authAsAdmin(context.Background())

	arg := AlbumRequest{
		Title:           "a very too long big enormous title that will never fit in any screen...",
		MetaDescription: "meta",
		Slug:            "a-good-title",
		PublishedAt:     sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
	}

	_, err := s.PostAlbum(ctx, arg)

	assert.Error(t, err)
	validationErrors := err.(validator.ValidationErrors)
	assert.Equal(t, "AlbumRequest.Title", validationErrors[0].Namespace())
	assert.Equal(t, "lt", validationErrors[0].ActualTag())

	total, err := db.CountAlbums(ctx, true)
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithEmptyMetaDescription(t *testing.T) {
	database2.ClearDB(db)
	ctx, _ := authAsAdmin(context.Background())

	arg := AlbumRequest{
		Title:           "a good title",
		MetaDescription: "",
		Slug:            "a-good-title",
		PublishedAt:     sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
	}

	_, err := s.PostAlbum(ctx, arg)

	assert.Error(t, err)
	validationErrors := err.(validator.ValidationErrors)
	assert.Equal(t, "AlbumRequest.MetaDescription", validationErrors[0].Namespace())
	assert.Equal(t, "required", validationErrors[0].ActualTag())

	total, err := db.CountAlbums(ctx, true)
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithTooLongMetaDescription(t *testing.T) {
	database2.ClearDB(db)
	ctx, _ := authAsAdmin(context.Background())

	arg := AlbumRequest{
		Title:           "a good title",
		MetaDescription: "a very too long big enormous meta description that will never fit in any screen...",
		Slug:            "a-good-title",
		PublishedAt:     sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
	}

	_, err := s.PostAlbum(ctx, arg)

	assert.Error(t, err)
	validationErrors := err.(validator.ValidationErrors)
	assert.Equal(t, "AlbumRequest.MetaDescription", validationErrors[0].Namespace())
	assert.Equal(t, "lt", validationErrors[0].ActualTag())

	total, err := db.CountAlbums(ctx, true)
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToPostAlbumAsUser(t *testing.T) {
	database2.ClearDB(db)
	ctx, _ := authAsUser(context.Background())

	arg := AlbumRequest{
		Title:           "a good title",
		MetaDescription: "meta",
		Slug:            "a-good-title",
		PublishedAt:     sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
	}

	_, err := s.PostAlbum(ctx, arg)

	assert.Error(t, err)
	assert.Equal(t, ErrNotAdmin, err)
	total, err := db.CountAlbums(ctx, true)
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToPostAlbumAsGuest(t *testing.T) {
	database2.ClearDB(db)

	arg := AlbumRequest{
		Title:           "a good title",
		MetaDescription: "meta",
		Slug:            "a-good-title",
		PublishedAt:     sql.NullTime{Time: time.Now().Add(-5 * time.Minute), Valid: true},
	}

	_, err := s.PostAlbum(context.Background(), arg)

	assert.Error(t, err)
	assert.Equal(t, ErrNoAuth, err)
	total, err := db.CountAlbums(context.Background(), true)
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

// //////// UPDATE //////////

// func TestShouldBeAbleToUpdateAlbumTitleAsAdmin(t *testing.T) {
// 	database2.ClearDB(db)
// 	ctx, _ := authAsAdmin(context.Background())

// 	a := AlbumModel{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
// 	db.Create(&a)

// 	a.Title = "A new Title"
// 	new, err := s.PutAlbum(ctx, a.Slug, AlbumRequest(a))

// 	assert.NoError(t, err)
// 	assert.Equal(t, "A new Title", new.Title)
// 	var total int64
// 	db.Model(&AlbumModel{}).Count(&total)
// 	assert.Equal(t, 1, int(total))
// }

// func TestShouldNotBeAbleToUpdateAlbumTooShortTitleAsAdmin(t *testing.T) {
// 	database2.ClearDB(db)
// 	a := AlbumModel{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
// 	db.Create(&a)

// 	a.Title = ""
// 	_, err := s.PutAlbum(context.Background(), a.Slug, AlbumRequest(a))

// 	assert.Error(t, err)
// 	var total int64
// 	db.Model(&AlbumModel{}).Count(&total)
// 	assert.Equal(t, 1, int(total))
// }

// func TestShouldNotBeAbleToUpdateAlbumAsUser(t *testing.T) {
// 	database2.ClearDB(db)
// 	a := AlbumModel{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
// 	ctx, _ := authAsUser(context.Background())
// 	db.Create(&a)

// 	_, err := s.PutAlbum(ctx, a.Slug, AlbumRequest(a))

// 	assert.Error(t, err)
// 	assert.Equal(t, ErrNotAdmin, err)
// 	var total int64
// 	db.Model(&AlbumModel{}).Count(&total)
// 	assert.Equal(t, 1, int(total))
// }

// func TestShouldNotBeAbleToUpdateAlbumAsGuest(t *testing.T) {
// 	database2.ClearDB(db)
// 	a := AlbumModel{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
// 	db.Create(&a)

// 	_, err := s.PutAlbum(context.Background(), a.Slug, AlbumRequest(a))

// 	assert.Error(t, err)
// 	assert.Equal(t, ErrNoAuth, err)
// 	var total int64
// 	db.Model(&AlbumModel{}).Count(&total)
// 	assert.Equal(t, 1, int(total))
// }

// //////// DELETE //////////

// func TestShouldBeAbleToDeleteAlbumAndNotSoftDeleted(t *testing.T) {
// 	database2.ClearDB(db)
// 	a := AlbumModel{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
// 	err := db.Create(&a).Error
// 	if err != nil {
// 		t.Error(err)
// 	}

// 	err = s.DeleteAlbum(context.Background(), a.Slug)

// 	var total, totalScopeless int64
// 	db.Model(&a).Count(&total)
// 	db.Model(&a).Unscoped().Count(&totalScopeless)
// 	assert.NoError(t, err)
// 	assert.Equal(t, 0, int(total))
// 	assert.Equal(t, 0, int(totalScopeless))
// }

// func TestShouldNotBeAbleToDeleteAnNonExistantAlbum(t *testing.T) {
// 	database2.ClearDB(db)
// 	err := s.DeleteAlbum(context.Background(), "a-random-slug")

// 	assert.Error(t, err)
// 	assert.EqualError(t, err, ErrNotFound.Error())
// }
