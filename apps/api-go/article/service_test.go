package article

import (
	"api-go/auth"
	"api-go/config"
	"api-go/database"
	articlesgrpc "api-go/gen/go/proto/articles/v1"
	"api-go/model"
	"context"
	"os"
	"strconv"
	"testing"
	"time"

	"github.com/davecgh/go-spew/spew"
	"github.com/kr/pretty"

	"github.com/stretchr/testify/assert"
	"google.golang.org/protobuf/types/known/timestamppb"
	"gorm.io/gorm"
)

var (
	s  articlesgrpc.ArticleServiceServer
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
	s = NewService(db)

	exitVal := m.Run() // Run tests
	// Do stuff after test

	os.Exit(exitVal)
}

/////// LIST ////////

func TestShouldBeAbleToListEmpty(t *testing.T) {
	database.ClearDB(db)
	r, _ := s.Index(context.Background(), &articlesgrpc.IndexRequest{})

	assert.Equal(t, 0, len(r.Data))
	// assert.Equal(t, int64(0), r.Meta.Total)
	// assert.Equal(t, 10, r.Meta.Limit)
}

func TestShouldBeAbleToListWithOnePublishedArticle(t *testing.T) {
	database.ClearDB(db)
	n := time.Now()
	a := model.Article{Name: "A good name", PublishedAt: &n}
	err := db.Create(&a).Error
	if !assert.NoError(t, err) {
		t.Error(err)
	}

	r, err := s.Index(context.Background(), &articlesgrpc.IndexRequest{})

	// assert.Equal(t, int64(1), r.Meta.Total)
	// assert.Equal(t, 10, r.Meta.Limit)
	spew.Dump(r.Data)
	assert.NoError(t, err)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeAbleToListPublishedArticlesOnSecondPage(t *testing.T) {
	var articles []model.Article
	database.ClearDB(db)
	n := time.Now()
	for i := 0; i < 10; i++ {
		tmp := model.Article{
			Name:        "A good name " + strconv.Itoa(i),
			PublishedAt: &n,
		}
		err := db.Create(&tmp).Error
		if err != nil {
			t.Error(err)
		}
		articles = append(articles, tmp)
	}
	a := model.Article{Name: "On second page", PublishedAt: &n}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	p := int32(articles[9].ID)
	r, _ := s.Index(context.Background(), &articlesgrpc.IndexRequest{Next: &p})

	// assert.Equal(t, int64(11), r.Meta.Total)
	// assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, a.Name, r.Data[0].Name)
}

func TestShouldBeAbleToListPublishedArticlesOnSecondPageWithCustomPerPage(t *testing.T) {
	var articles []model.Article
	n := time.Now()
	database.ClearDB(db)
	for i := 0; i < 2; i++ {
		tmp := model.Article{Name: "A good name " + strconv.Itoa(i), PublishedAt: &n}
		err := db.Create(&tmp).Error
		if err != nil {
			t.Error(err)
		}
		articles = append(articles, tmp)
	}
	a := model.Article{Name: "On second page", PublishedAt: &n}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	c := int32(articles[1].ID)
	l := int32(2)
	r, _ := s.Index(context.Background(), &articlesgrpc.IndexRequest{Next: &c, Limit: &l})

	// assert.Equal(t, int64(3), r.Meta.Total)
	// assert.Equal(t, 2, r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
	assert.Equal(t, a.Name, r.Data[0].Name)
}

func TestShouldBeAbleToListNonPublishedArticleAsAdmin(t *testing.T) {
	ctx, _ := authAsAdmin(context.Background())
	database.ClearDB(db)
	a := model.Article{Name: "A good name"}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	r, err := s.Index(ctx, &articlesgrpc.IndexRequest{})

	assert.NoError(t, err)
	// assert.Equal(t, int64(1), r.Meta.Total)
	// assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeAbleToListWithCustomPerPage(t *testing.T) {
	database.ClearDB(db)
	n := time.Now()
	a := model.Article{Name: "A good name", PublishedAt: &n}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	l := int32(2)
	r, _ := s.Index(context.Background(), &articlesgrpc.IndexRequest{Limit: &l})

	// assert.Equal(t, int64(1), r.Meta.Total)
	// assert.Equal(t, 15, r.Meta.Limit)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldNotListSoftDeletedArticles(t *testing.T) {
	database.ClearDB(db)
	n := time.Now()

	a := model.Article{Name: "A good name", PublishedAt: &n}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}
	db.Delete(&a)

	r, _ := s.Index(context.Background(), &articlesgrpc.IndexRequest{})

	// assert.Equal(t, int64(0), r.Meta.Total)
	// assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 0, len(r.Data))
}

func TestShouldNotListNonPublishedArticles(t *testing.T) {
	database.ClearDB(db)
	a := model.Article{Name: "A good name"}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	r, _ := s.Index(context.Background(), &articlesgrpc.IndexRequest{})

	// assert.Equal(t, int64(0), r.Meta.Total)
	// assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 0, len(r.Data))
}

///////// SHOW  ///////////

func TestShouldBeAbleToGetPublishedArticleAsGuest(t *testing.T) {
	database.ClearDB(db)
	n := time.Now()
	a := model.Article{Name: "A good name", Slug: "a-good-name", PublishedAt: &n}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	r, err := s.GetBySlug(context.Background(), &articlesgrpc.GetBySlugRequest{Slug: a.Slug})

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

func TestShouldBeAbleToGetPublishedArticleAsUser(t *testing.T) {
	database.ClearDB(db)
	ctx, _ := authAsUser(context.Background())
	n := time.Now()
	a := model.Article{Name: "A good name", Slug: "a-good-name", PublishedAt: &n}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	r, err := s.GetBySlug(ctx, &articlesgrpc.GetBySlugRequest{Slug: a.Slug})

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

func TestShouldNotBeAbleToGetNonPublishedArticleAsGuest(t *testing.T) {
	database.ClearDB(db)
	a := model.Article{Name: "A good name", Slug: "a-good-name"}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	_, err = s.GetBySlug(context.Background(), &articlesgrpc.GetBySlugRequest{Slug: a.Slug})

	assert.Error(t, err)
	assert.Equal(t, ErrNotFound, err)
}

func TestShouldNotBeAbleToGetNonPublishedArticleAsUser(t *testing.T) {
	database.ClearDB(db)
	ctx, _ := authAsUser(context.Background())
	a := model.Article{Name: "A good name", Slug: "a-good-name"}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	_, err = s.GetBySlug(ctx, &articlesgrpc.GetBySlugRequest{Slug: a.Slug})

	assert.Error(t, err)
	assert.Equal(t, ErrNotFound, err)
}

func TestShouldBeAbleToGetNonPublishedArticleAsAdmin(t *testing.T) {
	database.ClearDB(db)
	ctx, _ := authAsAdmin(context.Background())
	a := model.Article{Name: "A good name", Slug: "a-good-name"}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	r, err := s.GetBySlug(ctx, &articlesgrpc.GetBySlugRequest{Slug: a.Slug})

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

///////// POST  ///////////

func TestShouldBeAbleToCreateAnArticleAndGenerateSlugAsAdmin(t *testing.T) {
	database.ClearDB(db)
	a := articlesgrpc.CreateRequest{Name: "A good name", MetaDescription: "a meta decription"}
	ctx, claims := authAsAdmin(context.Background())

	pretty.Log(a)

	res, err := s.Create(ctx, &a)
	pretty.Log(res)

	assert.NoError(t, err)
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
	assert.Equal(t, a.Name, res.Name)
	assert.Equal(t, "a-good-name", res.Slug)
	assert.Equal(t, claims.Sub, res.AuthorId)
	assert.Nil(t, res.PublishedAt)
}

func TestShouldBeAbleToCreateAnPublishedArticleAndGenerateSlugAsAdmin(t *testing.T) {
	database.ClearDB(db)
	n := time.Now()
	a := articlesgrpc.CreateRequest{Name: "A good name", MetaDescription: "a meta decription", PublishedAt: &timestamppb.Timestamp{Seconds: n.Unix()}}
	ctx, claims := authAsAdmin(context.Background())

	res, err := s.Create(ctx, &a)

	assert.NoError(t, err)
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
	assert.Equal(t, a.Name, res.Name)
	assert.Equal(t, "a-good-name", res.Slug)
	assert.Equal(t, claims.Sub, res.AuthorId)
	assert.NotNil(t, res.PublishedAt)
}

func TestShouldBeAbleToCreateAnArticleWithASpecifiedSlug(t *testing.T) {
	database.ClearDB(db)
	a := articlesgrpc.CreateRequest{Name: "A good name", Slug: "wtf-is-this-slug", MetaDescription: "a meta decription"}
	ctx, claims := authAsAdmin(context.Background())

	res, err := s.Create(ctx, &a)

	assert.NoError(t, err)
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
	assert.Equal(t, a.Name, res.Name)
	assert.Equal(t, a.Slug, res.Slug)
	assert.Equal(t, claims.Sub, res.AuthorId)
}

func TestShouldNotBeAbleToCreateAnArticleWithSameSlug(t *testing.T) {
	database.ClearDB(db)
	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	db.Create(&a)
	dup := articlesgrpc.CreateRequest{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	ctx, _ := authAsAdmin(context.Background())

	_, err := s.Create(ctx, &dup)

	assert.Error(t, err)
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToSaveArticleWithEmptyName(t *testing.T) {
	database.ClearDB(db)
	a := articlesgrpc.CreateRequest{Name: ""}
	ctx, _ := authAsAdmin(context.Background())

	_, err := s.Create(ctx, &a)

	assert.Error(t, err)
	firstValidationError := err.(articlesgrpc.CreateRequestMultiError)[0].(articlesgrpc.CreateRequestValidationError)
	assert.Equal(t, "Name", firstValidationError.Field())
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveArticleWithTooLongName(t *testing.T) {
	database.ClearDB(db)
	a := articlesgrpc.CreateRequest{Name: "a very too long big enormous title that will never fit in any screen..."}
	ctx, _ := authAsAdmin(context.Background())

	_, err := s.Create(ctx, &a)

	assert.Error(t, err)
	firstValidationError := err.(articlesgrpc.CreateRequestMultiError)[0].(articlesgrpc.CreateRequestValidationError)
	assert.Equal(t, "Name", firstValidationError.Field())
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveArticleWithEmptyMetaDescription(t *testing.T) {
	database.ClearDB(db)
	a := articlesgrpc.CreateRequest{Name: "a good name", MetaDescription: ""}
	ctx, _ := authAsAdmin(context.Background())

	_, err := s.Create(ctx, &a)

	assert.Error(t, err)
	firstValidationError := err.(articlesgrpc.CreateRequestMultiError)[0].(articlesgrpc.CreateRequestValidationError)
	assert.Equal(t, "MetaDescription", firstValidationError.Field())
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveArticleWithTooLongMetaDescription(t *testing.T) {
	database.ClearDB(db)
	a := articlesgrpc.CreateRequest{Name: "A good name", MetaDescription: "a very too long big enormous title that will never fit in any screen...a very too long big enormous title that will never fit in any screen...a very too long big enormous title that will never fit in any screen..."}
	ctx, _ := authAsAdmin(context.Background())

	_, err := s.Create(ctx, &a)

	assert.Error(t, err)
	firstValidationError := err.(articlesgrpc.CreateRequestMultiError)[0].(articlesgrpc.CreateRequestValidationError)
	assert.Equal(t, "MetaDescription", firstValidationError.Field())
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToCreateAsUser(t *testing.T) {
	database.ClearDB(db)
	a := articlesgrpc.CreateRequest{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	ctx, _ := authAsUser(context.Background())

	_, err := s.Create(ctx, &a)

	assert.Error(t, err)
	assert.Equal(t, ErrNotAdmin, err)
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToCreateAsGuest(t *testing.T) {
	database.ClearDB(db)
	a := articlesgrpc.CreateRequest{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}

	_, err := s.Create(context.Background(), &a)

	assert.Error(t, err)
	assert.Equal(t, ErrNoAuth, err)
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

//////// UPDATE //////////

func TestShouldBeAbleToUpdateArticleNameAsAdmin(t *testing.T) {
	database.ClearDB(db)
	ctx, _ := authAsAdmin(context.Background())

	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	err := db.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	a.Name = "A new name"
	u := articlesgrpc.UpdateRequest{Id: a.ID, Name: a.Name, Slug: a.Slug, MetaDescription: a.MetaDescription}
	new, err := s.Update(ctx, &u)

	assert.NoError(t, err)
	assert.Equal(t, "A new name", new.Name)
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateArticleTooShortNameAsAdmin(t *testing.T) {
	database.ClearDB(db)
	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	db.Create(&a)

	u := &articlesgrpc.UpdateRequest{Name: "", Slug: a.Slug, MetaDescription: a.MetaDescription}
	_, err := s.Update(context.Background(), u)

	assert.Error(t, err)
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateArticleAsUser(t *testing.T) {
	database.ClearDB(db)
	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	ctx, _ := authAsUser(context.Background())
	db.Create(&a)

	u := articlesgrpc.UpdateRequest{Name: a.Name, Slug: a.Slug, MetaDescription: a.MetaDescription}
	_, err := s.Update(ctx, &u)

	assert.Error(t, err)
	assert.Equal(t, ErrNotAdmin, err)
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateArticleAsGuest(t *testing.T) {
	database.ClearDB(db)
	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	db.Create(&a)

	u := articlesgrpc.UpdateRequest{Name: a.Name, Slug: a.Slug, MetaDescription: a.MetaDescription}
	_, err := s.Update(context.Background(), &u)

	assert.Error(t, err)
	assert.Equal(t, ErrNoAuth, err)
	var total int64
	db.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

//////// DELETE //////////

func TestShouldBeAbleToDeleteArticleAndIsSoftDeleted(t *testing.T) {
	database.ClearDB(db)
	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	db.Create(&a)

	_, err := s.Delete(context.Background(), &articlesgrpc.DeleteRequest{Id: strconv.FormatInt(a.ID, 10)})

	var total, totalScopeless int64
	db.Model(&a).Count(&total)
	db.Model(&a).Unscoped().Count(&totalScopeless)
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
	assert.Equal(t, 1, int(totalScopeless))
}

func TestShouldNotBeAbleToDeleteAnNonExistantArticle(t *testing.T) {
	database.ClearDB(db)
	_, err := s.Delete(context.Background(), &articlesgrpc.DeleteRequest{Id: string("123123123")})

	assert.Error(t, err)
	assert.EqualError(t, err, ErrNotFound.Error())
}
