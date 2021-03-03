package article

import (
	"api-go/blog/auth"
	database "api-go/db"
	"context"
	"os"
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
	db, _ = database.Init()
	database.ClearDB(db)
	db.AutoMigrate(Article{})
	s = NewService(db)

	exitVal := m.Run() // Run tests
	// Do stuff after test

	os.Exit(exitVal)
}

func TestGetArticleList(t *testing.T) {

	t.Run("should be able to list empty", func(t *testing.T) {
		database.ClearDB(db)

		r, _ := s.GetArticleList(context.Background(), nil)

		assert.Equal(t, 0, len(r.Data))
		assert.Equal(t, int64(0), r.Meta.Total)
		assert.Equal(t, 10, r.Meta.PerPage)
	})

	t.Run("should be able to list with one published article", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", PublishedAt: null.NewTime(time.Now(), true)}
		db.Create(&a)

		r, _ := s.GetArticleList(context.Background(), nil)

		assert.Equal(t, int64(1), r.Meta.Total)
		assert.Equal(t, 10, r.Meta.PerPage)
		assert.Equal(t, 1, len(r.Data))
	})

	t.Run("should be able to list with custom per page", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", PublishedAt: null.NewTime(time.Now(), true)}
		db.Create(&a)

		r, _ := s.GetArticleList(context.Background(), &PaginationParams{1, 15})

		assert.Equal(t, int64(1), r.Meta.Total)
		assert.Equal(t, 15, r.Meta.PerPage)
		assert.Equal(t, 1, len(r.Data))
	})

	t.Run("should not list soft deleted articles", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", PublishedAt: null.NewTime(time.Now(), true)}
		db.Create(&a)
		db.Delete(&a)

		r, _ := s.GetArticleList(context.Background(), nil)

		assert.Equal(t, int64(0), r.Meta.Total)
		assert.Equal(t, 10, r.Meta.PerPage)
		assert.Equal(t, 0, len(r.Data))
	})

	t.Run("should not list non published articles", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name"}
		db.Create(&a)

		r, _ := s.GetArticleList(context.Background(), nil)

		assert.Equal(t, int64(0), r.Meta.Total)
		assert.Equal(t, 10, r.Meta.PerPage)
		assert.Equal(t, 0, len(r.Data))
	})
}

func TestPostArticle(t *testing.T) {
	t.Run("should be able to create an article and generate slug as admin", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", MetaDescription: "a meta decription"}
		ctx, claims := authAsAdmin(context.Background())

		res, err := s.PostArticle(ctx, a)

		assert.NoError(t, err)
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 1, int(total))
		assert.Equal(t, a.Name, res.Name)
		assert.Equal(t, "a-good-name", res.Slug)
		assert.Equal(t, claims.Sub, res.AuthorUUID)
		assert.False(t, res.PublishedAt.Valid)
	})

	t.Run("should be able to create an published article and generate slug as admin", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", MetaDescription: "a meta decription", PublishedAt: null.NewTime(time.Now(), true)}
		ctx, claims := authAsAdmin(context.Background())

		res, err := s.PostArticle(ctx, a)

		assert.NoError(t, err)
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 1, int(total))
		assert.Equal(t, a.Name, res.Name)
		assert.Equal(t, "a-good-name", res.Slug)
		assert.Equal(t, claims.Sub, res.AuthorUUID)
		assert.True(t, res.PublishedAt.Valid)
	})

	t.Run("should be able to create an article with a specified slug", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", Slug: "wtf-is-this-slug", MetaDescription: "a meta decription"}
		ctx, claims := authAsAdmin(context.Background())

		res, err := s.PostArticle(ctx, a)

		assert.NoError(t, err)
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 1, int(total))
		assert.Equal(t, a.Name, res.Name)
		assert.Equal(t, a.Slug, res.Slug)
		assert.Equal(t, claims.Sub, res.AuthorUUID)
	})

	t.Run("should not be able to create an article with same slug", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
		db.Create(&a)
		dup := Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
		ctx, _ := authAsAdmin(context.Background())

		_, err := s.PostArticle(ctx, dup)

		assert.Error(t, err)
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 1, int(total))
	})

	t.Run("should not be able to save article with empty name", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: ""}
		ctx, _ := authAsAdmin(context.Background())

		_, err := s.PostArticle(ctx, a)

		assert.Error(t, err)
		validationErrors := err.(validator.ValidationErrors)
		assert.Equal(t, "Article.Name", validationErrors[0].Namespace())
		assert.Equal(t, "required", validationErrors[0].ActualTag())
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 0, int(total))
	})

	t.Run("should not be able to save article with too long name", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "a very too long big enormous title that will never fit in any screen..."}
		ctx, _ := authAsAdmin(context.Background())

		_, err := s.PostArticle(ctx, a)

		assert.Error(t, err)
		validationErrors := err.(validator.ValidationErrors)
		assert.Equal(t, "Article.Name", validationErrors[0].Namespace())
		assert.Equal(t, "lt", validationErrors[0].ActualTag())
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 0, int(total))
	})

	t.Run("should not be able to save article with empty meta description", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "a good name", MetaDescription: ""}
		ctx, _ := authAsAdmin(context.Background())

		_, err := s.PostArticle(ctx, a)

		assert.Error(t, err)
		validationErrors := err.(validator.ValidationErrors)
		assert.Equal(t, "Article.MetaDescription", validationErrors[0].Namespace())
		assert.Equal(t, "required", validationErrors[0].ActualTag())
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 0, int(total))
	})

	t.Run("should not be able to save article with too meta description", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", MetaDescription: "a very too long big enormous title that will never fit in any screen..."}
		ctx, _ := authAsAdmin(context.Background())

		_, err := s.PostArticle(ctx, a)

		assert.Error(t, err)
		validationErrors := err.(validator.ValidationErrors)
		assert.Equal(t, "Article.MetaDescription", validationErrors[0].Namespace())
		assert.Equal(t, "lt", validationErrors[0].ActualTag())
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 0, int(total))
	})

	t.Run("should not be able to post article as user", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
		ctx, _ := authAsUser(context.Background())

		_, err := s.PostArticle(ctx, a)

		assert.Error(t, err)
		assert.Equal(t, ErrNotAdmin, err)
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 0, int(total))
	})

	t.Run("should not be able to post article as guest", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}

		_, err := s.PostArticle(context.Background(), a)

		assert.Error(t, err)
		assert.Equal(t, ErrNoAuth, err)
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 0, int(total))
	})
}

func TestDeleteArticle(t *testing.T) {
	t.Run("should be able to delete article and is soft deleted", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
		db.Create(&a)

		err := s.DeleteArticle(context.Background(), a.Slug)

		var total, totalScopeless int64
		db.Model(&a).Count(&total)
		db.Model(&a).Unscoped().Count(&totalScopeless)
		assert.NoError(t, err)
		assert.Equal(t, 0, int(total))
		assert.Equal(t, 1, int(totalScopeless))
	})

	t.Run("should not be able to delete an non existant article", func(t *testing.T) {
		database.ClearDB(db)

		err := s.DeleteArticle(context.Background(), "a-random-slug")

		assert.Error(t, err)
		assert.EqualError(t, err, ErrNotFound.Error())
	})
}
