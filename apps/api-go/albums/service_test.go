package album

import (
	"api-go/auth"
	"api-go/config"
	"api-go/database"
	"api-go/model"
	"api-go/query"
	"context"
	"errors"
	"fmt"
	"os"
	"strconv"
	"testing"
	"time"

	"github.com/go-playground/validator/v10"
	"github.com/jackc/pgconn"
	"github.com/jackc/pgerrcode"
	"github.com/stretchr/testify/assert"
	"gorm.io/gorm"
)

var (
	s   Service
	orm *gorm.DB
)

var ssoId = "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
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
	orm, _ = database.Init(config)
	database.ClearDB(orm)
	s = NewService(orm)

	exitVal := m.Run() // Run tests
	// Do stuff after test

	os.Exit(exitVal)
}

/////// LIST ////////

func TestShouldBeAbleToListEmpty(t *testing.T) {
	database.ClearDB(orm)
	r, err := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 10}})

	assert.Nil(t, err)
	assert.Equal(t, 0, len(r.Data))
	assert.Equal(t, int64(0), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
}

func TestShouldBeAbleToListWithOnePublishedAlbum(t *testing.T) {
	database.ClearDB(orm)
	sub10Min := time.Now().Add(-10 * time.Minute)
	a := query.Use(orm).Album

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub10Min,
		Private:     false, // todo is true wtf
		SsoID:       &ssoId,
	}
	err := a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(err)
	}

	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 10}})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeOrderedByDateOfPublication(t *testing.T) {
	database.ClearDB(orm)
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub100Min := time.Now().Add(-100 * time.Minute)
	sub10Min := time.Now().Add(-10 * time.Minute)
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(orm).Album

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
		err := a.WithContext(context.Background()).Create(&arg)
		if err != nil {
			t.Error(fmt.Errorf("unable create album: %w", err))
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
	database.ClearDB(orm)
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub100Min := time.Now().Add(-100 * time.Minute)
	sub10Min := time.Now().Add(-10 * time.Minute)
	a := query.Use(orm).Album

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

	for _, arg := range args {
		err := a.WithContext(context.Background()).Create(&arg)
		if err != nil {
			t.Error(fmt.Errorf("Error creating album: %w", err))
		}
	}

	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 10}})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, &ssoId, r.Data[0].SsoID)
}

func TestShouldBeAbleToListPublishedAlbumsOnSecondPage(t *testing.T) {
	database.ClearDB(orm)
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub100Min := time.Now().Add(-100 * time.Minute)
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(orm).Album

	var lastPageID int32

	for i := 0; i < 10; i++ {
		arg := model.Album{
			Title:       "A good Title " + strconv.Itoa(i),
			Slug:        "a-good-title-" + strconv.Itoa(i),
			PublishedAt: &sub100Min,
			Private:     false,
			SsoID:       &ssoId,
		}

		err := a.WithContext(context.Background()).Create(&arg)
		if i == 9 {
			lastPageID = arg.ID
		}
		if err != nil {
			t.Error(fmt.Errorf("Error creating album: %w", err))
		}
	}

	arg := model.Album{
		Title:       "On second page",
		Slug:        "on-second-page",
		PublishedAt: &sub5Min,
		Private:     false,
		SsoID:       &ssoId,
	}

	err := a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{Next: lastPageID, Limit: 10}})

	assert.Equal(t, int64(11), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, arg.Title, r.Data[0].Title)
}

func TestShouldBeAbleToListPublishedAlbumsOnSecondPageWithCustomPerPage(t *testing.T) {
	database.ClearDB(orm)

	var albums []model.Album
	var lastPageID int32
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(orm).Album

	for i := 0; i < 2; i++ {
		arg := model.Album{
			Title:       "On second page " + strconv.Itoa(i),
			Slug:        "on-second-page " + strconv.Itoa(i),
			PublishedAt: &sub5Min,
			Private:     false,
			SsoID:       &ssoId,
		}

		err := a.WithContext(context.Background()).Create(&arg)
		if err != nil {
			t.Error(fmt.Errorf("Error creating album: %w", err))
		}

		if i == 1 {
			lastPageID = arg.ID
		}

		albums = append(albums, arg)
	}

	arg := model.Album{
		Title:       "On second page",
		Slug:        "on-second-page",
		PublishedAt: &sub5Min,
		Private:     false,
		SsoID:       &ssoId,
	}

	err := a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{Next: lastPageID, Limit: 2}})

	assert.Equal(t, int64(3), r.Meta.Total)
	assert.Equal(t, int32(2), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, arg.Title, r.Data[0].Title)
}

func TestShouldBeAbleToListNonPublishedAlbumAsAdmin(t *testing.T) {
	ctx, _ := authAsAdmin(context.Background())
	database.ClearDB(orm)
	a := query.Use(orm).Album

	arg := model.Album{
		Title: "On second page",
		Slug:  "on-second-page",
		SsoID: &ssoId,
	}

	err := a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, _ := s.GetAlbumList(ctx, AlbumListParams{PaginationParams: PaginationParams{0, 10}})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeAbleToListWithCustomPerPage(t *testing.T) {
	database.ClearDB(orm)
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(orm).Album

	arg := model.Album{
		Title:       "On second page",
		Slug:        "on-second-page",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
	}

	err := a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, _ := s.GetAlbumList(context.Background(), AlbumListParams{PaginationParams: PaginationParams{0, 15}})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(15), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeAbleToListWithCategories(t *testing.T) {
	database.ClearDB(orm)
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(orm).Album
	c := query.Use(orm).Category

	arg1 := model.Category{
		Name: "A good Category",
	}
	err := c.WithContext(context.Background()).Create(&arg1)
	if err != nil {
		t.Error(fmt.Errorf("unable create category: %w", err))
	}

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
		// Categories:  &categories,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	err = a.Categories.Model(&arg).Append(&arg1)
	if err != nil {
		t.Error(fmt.Errorf("error linking album with category : %w", err))
	}

	r, err := s.GetAlbumList(context.Background(), AlbumListParams{Joins: AlbumListJoinsParams{Categories: true}, PaginationParams: PaginationParams{0, 10}})
	if err != nil {
		t.Error(err)
	}

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.NotNil(t, r.Data[0].Categories)
	assert.Equal(t, 1, len(*r.Data[0].Categories))
}

func TestShouldBeAbleToListWithoutCategories(t *testing.T) {
	database.ClearDB(orm)
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(orm).Album
	c := query.Use(orm).Category

	arg1 := model.Category{
		Name: "A good Category",
	}
	err := c.WithContext(context.Background()).Create(&arg1)

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
		// Categories:  &categories,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	err = a.Categories.Model(&arg).Append(&arg1)
	if err != nil {
		t.Error(fmt.Errorf("error linking album with category : %w", err))
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
	database.ClearDB(orm)
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(orm).Album
	m := query.Use(orm).Medium

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
	}

	err := a.WithContext(context.Background()).Create(&arg)
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

	r, err := s.GetAlbumList(context.Background(), AlbumListParams{Joins: AlbumListJoinsParams{Medias: true}, PaginationParams: PaginationParams{0, 10}})
	if err != nil {
		t.Error(err)
	}

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, int32(10), r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.NotNil(t, r.Data[0].Medias)
	assert.Equal(t, 1, len(*r.Data[0].Medias))
	assert.Equal(t, arg1.Name, (*r.Data[0].Medias)[0].Name)
}

func TestShouldNotListNonPublishedAlbums(t *testing.T) {
	database.ClearDB(orm)
	a := query.Use(orm).Album

	arg := model.Album{
		Title:   "A good Title",
		SsoID:   &ssoId,
		Private: false,
	}

	err := a.WithContext(context.Background()).Create(&arg)
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
	database.ClearDB(orm)
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(orm).Album

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
	}

	err := a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetAlbum(context.Background(), arg.Slug)

	assert.NoError(t, err)
	assert.Equal(t, arg.Slug, r.Slug)
}

func TestShouldBeAbleToGetPublishedAlbumAsUser(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsUser(context.Background())
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(orm).Album

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     false,
	}

	err := a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetAlbum(ctx, arg.Slug)

	assert.NoError(t, err)
	assert.Equal(t, arg.Slug, r.Slug)
}

func TestShouldNotBeAbleToGetNonPublishedAlbumAsGuest(t *testing.T) {
	database.ClearDB(orm)
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := query.Use(orm).Album

	slug := "a-good-title"
	arg := model.Album{
		Title: "A good Title",
		SsoID: &ssoId,
		Slug:  slug,
	}

	err := a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	_, err = s.GetAlbum(context.Background(), slug)

	assert.Error(t, err)
	assert.Equal(t, ErrNotFound, err)
}

func TestShouldNotBeAbleToGetNonPublishedAlbumAsUser(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsUser(context.Background())
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := query.Use(orm).Album

	arg := model.Album{
		Title: "A good Title",
		SsoID: &ssoId,
	}

	err := a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	_, err = s.GetAlbum(ctx, arg.Slug)

	assert.Error(t, err)
	assert.Equal(t, ErrNotFound, err)
}

func TestShouldBeAbleToGetNonPublishedAlbumAsAdmin(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(orm).Album

	arg := model.Album{
		Title: "A good Title",
		SsoID: &ssoId,
	}

	err := a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetAlbum(ctx, arg.Slug)

	assert.NoError(t, err)
	assert.Equal(t, arg.Slug, r.Slug)
}

// ///////// POST  ///////////

func TestShouldBeAbleToCreateAnAlbumAsAdmin(t *testing.T) {
	database.ClearDB(orm)
	ctx, claims := authAsAdmin(context.Background())
	a := query.Use(orm).Album

	slug := "a-good-title"
	arg := AlbumRequest{
		Title:           "A good Title",
		MetaDescription: "meta",
		Slug:            &slug,
	}

	res, err := s.PostAlbum(ctx, arg)

	assert.NoError(t, err)
	total, err := a.WithContext(context.Background()).Count()
	if err != nil {
		t.Error(fmt.Errorf("Error counting albums: %w", err))
	}
	assert.Equal(t, 1, int(total))
	assert.Equal(t, arg.Title, res.Title)
	assert.Equal(t, "a-good-title", res.Slug)
	assert.Equal(t, &claims.Sub, res.SsoID)
	assert.Equal(t, arg.PublishedAt, res.PublishedAt)
}

func TestShouldBeAbleToCreateAnPublishedAlbumAsAdmin(t *testing.T) {
	database.ClearDB(orm)
	ctx, claims := authAsAdmin(context.Background())
	a := query.Use(orm).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute).UTC()
	arg := AlbumRequest{
		Title:           "A good Title",
		MetaDescription: "meta",
		Slug:            &slug,
		PublishedAt:     &pub,
	}

	res, err := s.PostAlbum(ctx, arg)

	assert.NoError(t, err)
	total, err := a.WithContext(context.Background()).Count()

	if err != nil {
		t.Error(fmt.Errorf("Error counting albums: %w", err))
	}
	assert.Equal(t, 1, int(total))
	assert.Equal(t, arg.Title, res.Title)
	assert.Equal(t, "a-good-title", res.Slug)
	assert.Equal(t, &claims.Sub, res.SsoID)
	assert.Equal(t, arg.PublishedAt.Hour(), res.PublishedAt.Hour())
	assert.Equal(t, arg.PublishedAt.Minute(), res.PublishedAt.Minute())
	assert.Equal(t, arg.PublishedAt.Day(), res.PublishedAt.Day())
}

func TestShouldNotBeAbleToCreateAnAlbumWithSameSlug(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(orm).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := AlbumRequest{
		Title:           "A good Title",
		MetaDescription: "meta",
		Slug:            &slug,
		PublishedAt:     &pub,
	}

	_, err := s.PostAlbum(ctx, arg)
	assert.NoError(t, err)

	_, err = s.PostAlbum(ctx, arg)

	var pgErr *pgconn.PgError
	assert.Error(t, err)
	assert.True(t, errors.As(err, &pgErr))
	assert.Equal(t, pgerrcode.UniqueViolation, pgErr.Code)

	total, err := a.WithContext(context.Background()).Count()

	assert.NoError(t, err)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithEmptyTitle(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(orm).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := AlbumRequest{
		Title:           "",
		MetaDescription: "meta",
		Slug:            &slug,
		PublishedAt:     &pub,
	}

	_, err := s.PostAlbum(ctx, arg)

	assert.Error(t, err)
	assert.Equal(t, "Key: 'AlbumRequest.Title' Error:Field validation for 'Title' failed on the 'required' tag", err.Error())

	total, err := a.WithContext(context.Background()).Count()

	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithTooLongTitle(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(orm).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := AlbumRequest{
		Title:           "a very too long big enormous title that will never fit in any screen...",
		MetaDescription: "meta",
		Slug:            &slug,
		PublishedAt:     &pub,
	}

	_, err := s.PostAlbum(ctx, arg)

	assert.Error(t, err)
	validationErrors := err.(validator.ValidationErrors)
	assert.Equal(t, "AlbumRequest.Title", validationErrors[0].Namespace())
	assert.Equal(t, "lt", validationErrors[0].ActualTag())

	total, err := a.WithContext(context.Background()).Count()

	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithEmptyMetaDescription(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(orm).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := AlbumRequest{
		Title:           "a good title",
		MetaDescription: "",
		Slug:            &slug,
		PublishedAt:     &pub,
	}

	_, err := s.PostAlbum(ctx, arg)

	assert.Error(t, err)
	validationErrors := err.(validator.ValidationErrors)
	assert.Equal(t, "AlbumRequest.MetaDescription", validationErrors[0].Namespace())
	assert.Equal(t, "required", validationErrors[0].ActualTag())

	total, err := a.WithContext(context.Background()).Count()

	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithTooLongMetaDescription(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(orm).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := AlbumRequest{
		Title:           "a good title",
		MetaDescription: "a very too long big enormous meta description that will never fit in any screen...",
		Slug:            &slug,
		PublishedAt:     &pub,
	}

	_, err := s.PostAlbum(ctx, arg)

	assert.Error(t, err)
	validationErrors := err.(validator.ValidationErrors)
	assert.Equal(t, "AlbumRequest.MetaDescription", validationErrors[0].Namespace())
	assert.Equal(t, "lt", validationErrors[0].ActualTag())

	total, err := a.WithContext(context.Background()).Count()

	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToPostAlbumAsUser(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsUser(context.Background())
	a := query.Use(orm).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := AlbumRequest{
		Title:           "a good title",
		MetaDescription: "meta",
		Slug:            &slug,
		PublishedAt:     &pub,
	}

	_, err := s.PostAlbum(ctx, arg)

	assert.Error(t, err)
	assert.Equal(t, ErrNotAdmin, err)
	total, err := a.WithContext(context.Background()).Count()

	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToPostAlbumAsGuest(t *testing.T) {
	database.ClearDB(orm)
	a := query.Use(orm).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := AlbumRequest{
		Title:           "a good title",
		MetaDescription: "meta",
		Slug:            &slug,
		PublishedAt:     &pub,
	}

	_, err := s.PostAlbum(context.Background(), arg)

	assert.Error(t, err)
	assert.Equal(t, ErrNoAuth, err)
	total, err := a.WithContext(context.Background()).Count() // TODO Add filter published to pass test
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

// //////// UPDATE //////////

func TestShouldBeAbleToUpdateAlbumTitleAsAdmin(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsAdmin(context.Background())

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	orm.Create(&a)

	expectedTitle := "A new Title"
	new, err := s.PutAlbum(ctx, a.Slug, AlbumRequest{
		ID:              a.ID,
		Slug:            &a.Slug,
		Title:           expectedTitle,
		MetaDescription: a.MetaDescription,
	})

	assert.NoError(t, err)
	assert.Equal(t, expectedTitle, new.Title)
	var total int64
	orm.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateAlbumTooShortTitleAsAdmin(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsAdmin(context.Background())

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	orm.Create(&a)

	_, err := s.PutAlbum(ctx, a.Slug, AlbumRequest{
		ID:              a.ID,
		Slug:            &a.Slug,
		Title:           "",
		MetaDescription: a.MetaDescription,
	})

	assert.Error(t, err)
	assert.IsType(t, validator.ValidationErrors{}, err)
	var total int64
	orm.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateAlbumAsUser(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsUser(context.Background())

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	orm.Create(&a)

	_, err := s.PutAlbum(ctx, a.Slug, AlbumRequest{
		ID:              a.ID,
		Slug:            &a.Slug,
		Title:           "A new Title",
		MetaDescription: a.MetaDescription,
	})

	assert.Error(t, err)
	assert.Equal(t, ErrNotAdmin, err)
	var total int64
	orm.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateAlbumAsGuest(t *testing.T) {
	database.ClearDB(orm)
	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	orm.Create(&a)

	_, err := s.PutAlbum(context.Background(), a.Slug, AlbumRequest{
		ID:              a.ID,
		Slug:            &a.Slug,
		Title:           "A new Title",
		MetaDescription: a.MetaDescription,
	})

	assert.Error(t, err)
	assert.Equal(t, ErrNoAuth, err)
	var total int64
	orm.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

// //////// DELETE //////////

func TestAdminShouldBeAbleToDeleteAlbumAndNotSoftDeleted(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsAdmin(context.Background())
	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	err := orm.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	err = s.DeleteAlbum(ctx, a.Slug)

	var total, totalScopeless int64
	orm.Model(&a).Count(&total)
	orm.Model(&a).Unscoped().Count(&totalScopeless)
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
	assert.Equal(t, 0, int(totalScopeless))
}

func TestAdminShouldNotBeAbleToDeleteAnNonExistantAlbum(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsAdmin(context.Background())

	err := s.DeleteAlbum(ctx, "non-existant-slug")

	assert.Error(t, err)
	assert.EqualError(t, err, ErrNotFound.Error())
}

func TestUserShouldNotBeAbleToDelete(t *testing.T) {
	database.ClearDB(orm)
	ctx, _ := authAsUser(context.Background())
	slug := "a-good-slug"
	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            slug,
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	err := orm.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	err = s.DeleteAlbum(ctx, slug)

	assert.Error(t, err)
	assert.EqualError(t, err, ErrNotAdmin.Error())
}

func TestGuestShouldNotBeAbleToDelete(t *testing.T) {
	database.ClearDB(orm)

	err := s.DeleteAlbum(context.Background(), "a-random-slug")

	assert.Error(t, err)
	assert.EqualError(t, err, ErrNoAuth.Error())
}
