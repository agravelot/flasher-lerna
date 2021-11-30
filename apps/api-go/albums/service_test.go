package album

import (
	"api-go/auth"
	"api-go/config"
	database "api-go/db"
	"context"
	"os"
	"strconv"
	"testing"
	"time"

	"github.com/go-playground/validator/v10"
	"github.com/guregu/null"
	"github.com/stretchr/testify/assert"
	"gorm.io/gorm"
)

var (
	s  Service
	db *gorm.DB
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
	db, _ = database.Init(config)
	database.ClearDB(db)
	db.AutoMigrate(&Media{})
	db.AutoMigrate(&Album{})
	db.AutoMigrate(&Category{})
	db.AutoMigrate(&AlbumCategory{})
	s = NewService(db)

	exitVal := m.Run() // Run tests
	// Do stuff after test

	os.Exit(exitVal)
}

/////// LIST ////////

func TestShouldBeAbleToListEmpty(t *testing.T) {
	database.ClearDB(db)
	r, _ := s.GetAlbumList(context.Background(), PaginationParams{0, 10})

	assert.Equal(t, 0, len(r.Data))
	assert.Equal(t, int64(0), r.Meta.Total)
	assert.Equal(t, 10, r.Meta.Limit)
}

func boolPtr(b bool) *bool {
	return &b
}

func TestShouldBeAbleToListWithOnePublishedAlbum(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title aze", PublishedAt: null.NewTime(time.Now().Add(-10*time.Minute), true), Private: boolPtr(false), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	r, _ := s.GetAlbumList(context.Background(), PaginationParams{0, 10})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeOrderedByDateOfPublication(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", PublishedAt: null.NewTime(time.Now().Add(-100*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
	b := Album{Title: "A good Title 2", PublishedAt: null.NewTime(time.Now().Add(-10*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
	c := Album{Title: "A good Title 3", PublishedAt: null.NewTime(time.Now().Add(-5*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}
	err = db.Create(&b).Error
	if err != nil {
		t.Error(err)
	}
	err = db.Create(&c).Error
	if err != nil {
		t.Error(err)
	}

	r, _ := s.GetAlbumList(context.Background(), PaginationParams{0, 10})

	assert.Equal(t, int64(3), r.Meta.Total)
	assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 3, len(r.Data))
	assert.Equal(t, c.Slug, r.Data[0].Slug)
	assert.Equal(t, b.Slug, r.Data[1].Slug)
	assert.Equal(t, a.Slug, r.Data[2].Slug)
}

func TestShouldOnlyShowPublicAlbums(t *testing.T) {
	database.ClearDB(db)
	albums := []Album{
		{Title: "A good Title", PublishedAt: null.NewTime(time.Now().Add(-10*time.Minute), true), Private: boolPtr(false), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"},
		{Title: "A good Title 2", PublishedAt: null.NewTime(time.Now().Add(-10*time.Minute), true), Private: boolPtr(true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"},
		{Title: "A good Title 3", Private: boolPtr(true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"},
		{Title: "A good Title 4", Private: boolPtr(false), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"},
	}

	for _, a := range albums {
		err := db.Create(&a).Error
		if err != nil {
			t.Error(err)
		}
	}

	r, _ := s.GetAlbumList(context.Background(), PaginationParams{0, 10})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeAbleToListPublishedAlbumsOnSecondPage(t *testing.T) {
	database.ClearDB(db)
	var albums []Album
	for i := 0; i < 10; i++ {
		tmp := Album{Title: "A good Title " + strconv.Itoa(i), PublishedAt: null.NewTime(time.Now().Add(-5*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
		db.Create(&tmp)
		albums = append(albums, tmp)
	}
	a := Album{Title: "On second page", PublishedAt: null.NewTime(time.Now().Add(-5*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
	db.Create(&a)

	r, _ := s.GetAlbumList(context.Background(), PaginationParams{Next: albums[9].ID, Limit: 10})

	assert.Equal(t, int64(11), r.Meta.Total)
	assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, a.Title, r.Data[0].Title)
}

func TestShouldBeAbleToListPublishedAlbumsOnSecondPageWithCustomPerPage(t *testing.T) {
	var albums []Album
	database.ClearDB(db)
	for i := 0; i < 2; i++ {
		tmp := Album{Title: "A good Title " + strconv.Itoa(i), PublishedAt: null.NewTime(time.Now().Add(-5*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
		db.Create(&tmp)
		albums = append(albums, tmp)
	}
	a := Album{Title: "On second page", PublishedAt: null.NewTime(time.Now().Add(-5*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
	db.Create(&a)

	r, _ := s.GetAlbumList(context.Background(), PaginationParams{Next: albums[1].ID, Limit: 2})

	assert.Equal(t, int64(3), r.Meta.Total)
	assert.Equal(t, 2, r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, a.Title, r.Data[0].Title)
}

func TestShouldBeAbleToListNonPublishedAlbumAsAdmin(t *testing.T) {
	ctx, _ := authAsAdmin(context.Background())
	database.ClearDB(db)
	a := Album{Title: "A good Title", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	db.Create(&a)

	r, _ := s.GetAlbumList(ctx, PaginationParams{0, 10})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeAbleToListWithCustomPerPage(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", PublishedAt: null.NewTime(time.Now().Add(-5*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
	db.Create(&a)

	r, _ := s.GetAlbumList(context.Background(), PaginationParams{0, 15})

	assert.Equal(t, int64(1), r.Meta.Total)
	assert.Equal(t, 15, r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldNotListSoftDeletedAlbums(t *testing.T) {
	database.ClearDB(db)

	a := Album{Title: "A good Title", PublishedAt: null.NewTime(time.Now(), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	db.Create(&a)
	db.Delete(&a)

	r, _ := s.GetAlbumList(context.Background(), PaginationParams{0, 10})

	assert.Equal(t, int64(0), r.Meta.Total)
	assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 0, len(r.Data))
}

func TestShouldNotListNonPublishedAlbums(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title"}
	db.Create(&a)

	r, _ := s.GetAlbumList(context.Background(), PaginationParams{0, 10})

	assert.Equal(t, int64(0), r.Meta.Total)
	assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 0, len(r.Data))
}

///////// SHOW  ///////////

func TestShouldBeAbleToGetPublishedAlbumAsGuest(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", Slug: "a-good-title", PublishedAt: null.NewTime(time.Now().Add(-10*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
	db.Create(&a)

	r, err := s.GetAlbum(context.Background(), a.Slug)

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

func TestShouldBeAbleToGetPublishedAlbumAsUser(t *testing.T) {
	database.ClearDB(db)
	ctx, _ := authAsUser(context.Background())
	a := Album{Title: "A good Title", Slug: "a-good-title", PublishedAt: null.NewTime(time.Now().Add(-5*time.Minute), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11", Private: boolPtr(false)}
	db.Create(&a)

	r, err := s.GetAlbum(ctx, a.Slug)

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

func TestShouldNotBeAbleToGetNonPublishedAlbumAsGuest(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", Slug: "a-good-title"}
	db.Create(&a)

	_, err := s.GetAlbum(context.Background(), a.Slug)

	assert.Error(t, err)
	assert.Equal(t, ErrNotFound, err)
}

func TestShouldNotBeAbleToGetNonPublishedAlbumAsUser(t *testing.T) {
	database.ClearDB(db)
	ctx, _ := authAsUser(context.Background())
	a := Album{Title: "A good Title", Slug: "a-good-title", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	db.Create(&a)

	_, err := s.GetAlbum(ctx, a.Slug)

	assert.Error(t, err)
	assert.Equal(t, ErrNotFound, err)
}

func TestShouldBeAbleToGetNonPublishedAlbumAsAdmin(t *testing.T) {
	database.ClearDB(db)
	ctx, _ := authAsAdmin(context.Background())
	a := Album{Title: "A good Title", Slug: "a-good-title", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	db.Create(&a)

	r, err := s.GetAlbum(ctx, a.Slug)

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

///////// POST  ///////////

func TestShouldBeAbleToCreateAnAlbumAndGenerateSlugAsAdmin(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	ctx, claims := authAsAdmin(context.Background())

	res, err := s.PostAlbum(ctx, a)

	assert.NoError(t, err)
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
	assert.Equal(t, a.Title, res.Title)
	assert.Equal(t, "a-good-title", res.Slug)
	assert.Equal(t, claims.Sub, res.SsoID)
	assert.False(t, res.PublishedAt.Valid)
}

func TestShouldBeAbleToCreateAnPublishedAlbumAndGenerateSlugAsAdmin(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", MetaDescription: "a meta decription", PublishedAt: null.NewTime(time.Now(), true), SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	ctx, claims := authAsAdmin(context.Background())

	res, err := s.PostAlbum(ctx, a)

	assert.NoError(t, err)
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
	assert.Equal(t, a.Title, res.Title)
	assert.Equal(t, "a-good-title", res.Slug)
	assert.Equal(t, claims.Sub, res.SsoID)
	assert.True(t, res.PublishedAt.Valid)
}

func TestShouldBeAbleToCreateAnAlbumWithASpecifiedSlug(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", Slug: "wtf-is-this-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	ctx, claims := authAsAdmin(context.Background())

	res, err := s.PostAlbum(ctx, a)

	assert.NoError(t, err)
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
	assert.Equal(t, a.Title, res.Title)
	assert.Equal(t, a.Slug, res.Slug)
	assert.Equal(t, claims.Sub, res.SsoID)
}

func TestShouldNotBeAbleToCreateAnAlbumWithSameSlug(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	db.Create(&a)
	dup := Album{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	ctx, _ := authAsAdmin(context.Background())

	_, err := s.PostAlbum(ctx, dup)

	assert.Error(t, err)
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithEmptyTitle(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: ""}
	ctx, _ := authAsAdmin(context.Background())

	_, err := s.PostAlbum(ctx, a)

	assert.Error(t, err)
	validationErrors := err.(validator.ValidationErrors)
	assert.Equal(t, "Album.Title", validationErrors[0].Namespace())
	assert.Equal(t, "required", validationErrors[0].ActualTag())
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithTooLongTitle(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "a very too long big enormous title that will never fit in any screen...", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	ctx, _ := authAsAdmin(context.Background())

	_, err := s.PostAlbum(ctx, a)

	assert.Error(t, err)
	validationErrors := err.(validator.ValidationErrors)
	assert.Equal(t, "Album.Title", validationErrors[0].Namespace())
	assert.Equal(t, "lt", validationErrors[0].ActualTag())
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithEmptyMetaDescription(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "a good Title", MetaDescription: "", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	ctx, _ := authAsAdmin(context.Background())

	_, err := s.PostAlbum(ctx, a)

	assert.Error(t, err)
	validationErrors := err.(validator.ValidationErrors)
	assert.Equal(t, "Album.MetaDescription", validationErrors[0].Namespace())
	assert.Equal(t, "required", validationErrors[0].ActualTag())
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithTooLongMetaDescription(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", MetaDescription: "a very too long big enormous title that will never fit in any screen...", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	ctx, _ := authAsAdmin(context.Background())

	_, err := s.PostAlbum(ctx, a)

	assert.Error(t, err)
	validationErrors := err.(validator.ValidationErrors)
	assert.Equal(t, "Album.MetaDescription", validationErrors[0].Namespace())
	assert.Equal(t, "lt", validationErrors[0].ActualTag())
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToPostAlbumAsUser(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	ctx, _ := authAsUser(context.Background())

	_, err := s.PostAlbum(ctx, a)

	assert.Error(t, err)
	assert.Equal(t, ErrNotAdmin, err)
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToPostAlbumAsGuest(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}

	_, err := s.PostAlbum(context.Background(), a)

	assert.Error(t, err)
	assert.Equal(t, ErrNoAuth, err)
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

//////// UPDATE //////////

func TestShouldBeAbleToUpdateAlbumTitleAsAdmin(t *testing.T) {
	database.ClearDB(db)
	ctx, _ := authAsAdmin(context.Background())

	a := Album{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	db.Create(&a)

	a.Title = "A new Title"
	new, err := s.PutAlbum(ctx, a.Slug, a)

	assert.NoError(t, err)
	assert.Equal(t, "A new Title", new.Title)
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateAlbumTooShortTitleAsAdmin(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	db.Create(&a)

	a.Title = ""
	_, err := s.PutAlbum(context.Background(), a.Slug, a)

	assert.Error(t, err)
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateAlbumAsUser(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	ctx, _ := authAsUser(context.Background())
	db.Create(&a)

	_, err := s.PutAlbum(ctx, a.Slug, a)

	assert.Error(t, err)
	assert.Equal(t, ErrNotAdmin, err)
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateAlbumAsGuest(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	db.Create(&a)

	_, err := s.PutAlbum(context.Background(), a.Slug, a)

	assert.Error(t, err)
	assert.Equal(t, ErrNoAuth, err)
	var total int64
	db.Model(&Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

//////// DELETE //////////

func TestShouldBeAbleToDeleteAlbumAndNotSoftDeleted(t *testing.T) {
	database.ClearDB(db)
	a := Album{Title: "A good Title", Slug: "a-good-slug", MetaDescription: "a meta decription", SsoID: "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	err = s.DeleteAlbum(context.Background(), a.Slug)

	var total, totalScopeless int64
	db.Model(&a).Count(&total)
	db.Model(&a).Unscoped().Count(&totalScopeless)
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
	assert.Equal(t, 0, int(totalScopeless))
}

func TestShouldNotBeAbleToDeleteAnNonExistantAlbum(t *testing.T) {
	database.ClearDB(db)
	err := s.DeleteAlbum(context.Background(), "a-random-slug")

	assert.Error(t, err)
	assert.EqualError(t, err, ErrNotFound.Error())
}
