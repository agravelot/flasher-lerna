package article

import (
	database "api-go/db"
	"context"
	"os"
	"testing"

	"github.com/stretchr/testify/assert"
	"gorm.io/gorm"
)

var (
	s  Service
	db *gorm.DB
)

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

	t.Run("should be able to list with one article", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name"}
		db.Create(&a)

		r, _ := s.GetArticleList(context.Background(), nil)

		assert.Equal(t, int64(1), r.Meta.Total)
		assert.Equal(t, 10, r.Meta.PerPage)
		assert.Equal(t, 1, len(r.Data))
	})

	t.Run("should be able to list with custom per page", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name"}
		db.Create(&a)

		r, _ := s.GetArticleList(context.Background(), &PaginationParams{1, 15})

		assert.Equal(t, int64(1), r.Meta.Total)
		assert.Equal(t, 15, r.Meta.PerPage)
		assert.Equal(t, 1, len(r.Data))
	})

	t.Run("should not list soft deleted articles", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name"}
		db.Create(&a)
		db.Delete(&a)

		r, _ := s.GetArticleList(context.Background(), nil)

		assert.Equal(t, int64(0), r.Meta.Total)
		assert.Equal(t, 10, r.Meta.PerPage)
		assert.Equal(t, 0, len(r.Data))
	})
}

var token = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICIxamVHNzFZSHlUd25GUEVSb2NJeEVzS21lbjlWN1NjanRIZXFzak1KUXlZIn0.eyJleHAiOjE2MTExNjQ3MTAsImlhdCI6MTYxMTE2NDQxMCwiYXV0aF90aW1lIjoxNjExMTY0MzY0LCJqdGkiOiJlMThlMWNlOC05OTc5LTQ3NmQtOWYxMC1mOTk5OWJhMDQwZjgiLCJpc3MiOiJodHRwczovL2FjY291bnRzLmFncmF2ZWxvdC5ldS9hdXRoL3JlYWxtcy9hbnljbG91ZCIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiIzMDE1MWFlNS0yOGI0LTRjNmMtYjBhZS1lYTJlNmE0OWVmNjciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJmcm9udGVuZCIsIm5vbmNlIjoiMTkyOWEwZGEtMTU2ZS00NWZmLTgzM2YtYTU2MGIwNmI1YWNkIiwic2Vzc2lvbl9zdGF0ZSI6IjRlMWYxOWYzLTFhMmMtNGUxNS1iMWFhLTNlY2ZhMTkxMGRiOCIsImFjciI6IjAiLCJhbGxvd2VkLW9yaWdpbnMiOlsiaHR0cDovL2xvY2FsaG9zdDo4MDgwIiwiaHR0cDovL2xvY2FsaG9zdDo4MDgxIl0sInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgcHJvZmlsZSBlbWFpbCIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiJ0ZXN0IiwiZW1haWwiOiJ0ZXN0QHRlc3QuY29tIn0.PkfxSmIiG4lLE9hCjICcRPNpXC0X2QtVzYeUwAUwwe2G_6ArmMdZOkRVOKx3jiRO7PYu-D0NR9tAiv7yN9SDMDrIhtNoosgChB4PQ4wBf_YvHsJaAHwyK8Hu6h_8gxJIl3UYCKWTSYgLRK-IOE9E6FNlMdJK9UXAO_y2IBEZBO9QV-QxZH7SlYkm8VfoZzNzRMy82SgWLsQGDvwAAGCxHFRgTZdFNKPoqJylDyANBEuWanLwDohQKdNGqz6PlhtopmXo1v8kcHwBHxyMQ3mtRNCXBV6TOXo7oAWW3XeXGWjTtAiTY85Wr7R6IJ74WKpMrG-3PDL6Sx6n4JxOuurpLg"

func authAsUser(ctx context.Context) (context.Context, Claims) {
	claims := Claims{
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
		RealmAccess: RealmAccess{
			Roles: []string{},
		},
		ResourceAccess: ResourceAccess{
			Account: Account{
				Roles: []string{},
			},
		},
		Scope:             "openid profile email",
		EmailVerified:     true,
		PreferredUsername: "test",
		Email:             "test@test.com",
	}

	// println(UserClaimsKey)

	return context.WithValue(ctx, "user", claims), claims
}

// func printContextInternals(ctx interface{}, inner bool) {
// 	contextValues := reflect.ValueOf(ctx).Elem()
// 	contextKeys := reflect.TypeOf(ctx).Elem()

// 	if !inner {
// 		fmt.Printf("\nFields for %s.%s\n", contextKeys.PkgPath(), contextKeys.Name())
// 	}

// 	if contextKeys.Kind() == reflect.Struct {
// 		for i := 0; i < contextValues.NumField(); i++ {
// 			reflectValue := contextValues.Field(i)
// 			reflectValue = reflect.NewAt(reflectValue.Type(), unsafe.Pointer(reflectValue.UnsafeAddr())).Elem()

// 			reflectField := contextKeys.Field(i)

// 			if reflectField.Name == "Context" {
// 				printContextInternals(reflectValue.Interface(), true)
// 			} else {
// 				fmt.Printf("field name: %+v\n", reflectField.Name)
// 				fmt.Printf("value: %+v\n", reflectValue.Interface())
// 			}
// 		}
// 	} else {
// 		fmt.Printf("context is empty (int)\n")
// 	}
// }

func TestPostArticle(t *testing.T) {
	t.Run("should be able to create an article and generate slug as admin", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name"}
		ctx, claims := authAsUser(context.Background())

		res, err := s.PostArticle(ctx, a)

		assert.NoError(t, err)
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 1, int(total))
		assert.Equal(t, a.Name, res.Name)
		assert.Equal(t, "a-good-name", res.Slug)
		assert.Equal(t, claims.Sub, res.AuthorUUID)
	})

	t.Run("should be able to create an article with a specified slug", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", Slug: "wtf-is-this-slug"}
		ctx, _ := authAsUser(context.Background())

		res, err := s.PostArticle(ctx, a)

		assert.NoError(t, err)
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 1, int(total))
		assert.Equal(t, a.Name, res.Name)
		assert.Equal(t, a.Slug, res.Slug)
	})

	t.Run("should not be able to create an article with same slug", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", Slug: "a-good-slug"}
		db.Create(&a)
		dup := Article{Name: "A good name", Slug: "a-good-slug"}
		ctx, _ := authAsUser(context.Background())

		res, err := s.PostArticle(ctx, dup)

		assert.Error(t, err)
		println(err.Error())
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 1, int(total))
		assert.Equal(t, a.Name, res.Name)
	})
}

func TestDeleteArticle(t *testing.T) {
	t.Run("should be able to delete article and is soft deleted", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", Slug: "a-good-slug"}
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
