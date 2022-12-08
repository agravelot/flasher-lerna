package article_test

import (
	"api-go/infrastructure/auth"
	"context"
	"fmt"
	"log"
	"os"
	"strconv"
	"testing"
	"time"

	"api-go/config"
	"api-go/domain/article"
	articlesgrpc "api-go/gen/go/proto/articles/v2"
	"api-go/infrastructure/storage/postgres"
	"api-go/model"
	"github.com/stretchr/testify/assert"
	"google.golang.org/protobuf/types/known/timestamppb"
)

var db postgres.Postgres

func TestMain(m *testing.M) {
	c, err := config.FromDotEnv("../../.env")
	if err != nil {
		log.Fatal(fmt.Errorf("unable to load config: %w", err))
	}
	db2, err := postgres.New(c.Database.URL)
	if err != nil {
		log.Fatal(fmt.Errorf("unable to connect to the database: %w", err))
	}
	db = db2

	exitVal := m.Run() // Run tests
	// Do stuff after test

	os.Exit(exitVal)
}

/////// LIST ////////

func TestShouldBeAbleToListEmpty(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	r, err := s.Index(context.Background(), &articlesgrpc.IndexRequest{})

	assert.NoError(t, err)
	assert.Equal(t, 0, len(r.Data))
	// assert.Equal(t, int64(0), r.Meta.Total)
	// assert.Equal(t, 10, r.Meta.Limit)
}

func TestShouldBeAbleToListWithOnePublishedArticle(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	n := time.Now()
	a := model.Article{Name: "A good name", PublishedAt: &n}
	err = tx.DB.Create(&a).Error
	if !assert.NoError(t, err) {
		t.Error(err)
	}

	r, err := s.Index(context.Background(), &articlesgrpc.IndexRequest{})

	// assert.Equal(t, int64(1), r.Meta.Total)
	// assert.Equal(t, 10, r.Meta.Limit)
	assert.NoError(t, err)
	assert.Equal(t, 1, len(r.Data))
}

func TestShouldBeAbleToListPublishedArticlesOnSecondPage(t *testing.T) {
	var articles []model.Article
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	n := time.Now()
	for i := 0; i < 10; i++ {
		tmp := model.Article{
			Name:        "A good name " + strconv.Itoa(i),
			PublishedAt: &n,
		}
		err := tx.DB.Create(&tmp).Error
		if err != nil {
			t.Error(err)
		}
		articles = append(articles, tmp)
	}
	a := model.Article{Name: "On second page", PublishedAt: &n}
	err = tx.DB.Create(&a).Error
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
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	for i := 0; i < 2; i++ {
		tmp := model.Article{Name: "A good name " + strconv.Itoa(i), PublishedAt: &n}
		err := tx.DB.Create(&tmp).Error
		if err != nil {
			t.Error(err)
		}
		articles = append(articles, tmp)
	}
	a := model.Article{Name: "On second page", PublishedAt: &n}
	err = tx.DB.Create(&a).Error
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
	ctx, _ := auth.AuthAsAdmin(context.Background())
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := model.Article{Name: "A good name"}
	err = tx.DB.Create(&a).Error
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
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	n := time.Now()
	a := model.Article{Name: "A good name", PublishedAt: &n}
	err = tx.DB.Create(&a).Error
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
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	n := time.Now()

	a := model.Article{Name: "A good name", PublishedAt: &n}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}
	tx.DB.Delete(&a)

	r, _ := s.Index(context.Background(), &articlesgrpc.IndexRequest{})

	// assert.Equal(t, int64(0), r.Meta.Total)
	// assert.Equal(t, 10, r.Meta.Limit)
	assert.Equal(t, 0, len(r.Data))
}

func TestShouldNotListNonPublishedArticles(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := model.Article{Name: "A good name"}
	err = tx.DB.Create(&a).Error
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
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	n := time.Now()
	a := model.Article{Name: "A good name", Slug: "a-good-name", PublishedAt: &n}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	r, err := s.GetBySlug(context.Background(), &articlesgrpc.GetBySlugRequest{Slug: a.Slug})

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

func TestShouldBeAbleToGetPublishedArticleAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	ctx, _ := auth.AuthAsUser(context.Background())
	n := time.Now()
	a := model.Article{Name: "A good name", Slug: "a-good-name", PublishedAt: &n}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	r, err := s.GetBySlug(ctx, &articlesgrpc.GetBySlugRequest{Slug: a.Slug})

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

func TestShouldNotBeAbleToGetNonPublishedArticleAsGuest(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := model.Article{Name: "A good name", Slug: "a-good-name"}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	_, err = s.GetBySlug(context.Background(), &articlesgrpc.GetBySlugRequest{Slug: a.Slug})

	assert.Error(t, err)
	assert.Equal(t, article.ErrNotFound, err)
}

func TestShouldNotBeAbleToGetNonPublishedArticleAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	ctx, _ := auth.AuthAsUser(context.Background())
	a := model.Article{Name: "A good name", Slug: "a-good-name"}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	_, err = s.GetBySlug(ctx, &articlesgrpc.GetBySlugRequest{Slug: a.Slug})

	assert.Error(t, err)
	assert.Equal(t, article.ErrNotFound, err)
}

func TestShouldBeAbleToGetNonPublishedArticleAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	ctx, _ := auth.AuthAsAdmin(context.Background())
	a := model.Article{Name: "A good name", Slug: "a-good-name"}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	r, err := s.GetBySlug(ctx, &articlesgrpc.GetBySlugRequest{Slug: a.Slug})

	assert.NoError(t, err)
	assert.Equal(t, a.Slug, r.Slug)
}

///////// POST  ///////////

func TestShouldBeAbleToCreateAnArticleAndGenerateSlugAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := articlesgrpc.CreateRequest{Name: "A good name", MetaDescription: "a meta decription"}
	ctx, claims := auth.AuthAsAdmin(context.Background())

	res, err := s.Create(ctx, &a)

	assert.NoError(t, err)
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
	assert.Equal(t, a.Name, res.Name)
	assert.Equal(t, "a-good-name", res.Slug)
	assert.Equal(t, claims.Sub, res.AuthorId)
	assert.Nil(t, res.PublishedAt)
}

func TestShouldBeAbleToCreateAnPublishedArticleAndGenerateSlugAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	n := time.Now()
	a := articlesgrpc.CreateRequest{Name: "A good name", MetaDescription: "a meta decription", PublishedAt: &timestamppb.Timestamp{Seconds: n.Unix()}}
	ctx, claims := auth.AuthAsAdmin(context.Background())

	res, err := s.Create(ctx, &a)

	assert.NoError(t, err)
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
	assert.Equal(t, a.Name, res.Name)
	assert.Equal(t, "a-good-name", res.Slug)
	assert.Equal(t, claims.Sub, res.AuthorId)
	assert.NotNil(t, res.PublishedAt)
}

func TestShouldBeAbleToCreateAnArticleWithASpecifiedSlug(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := articlesgrpc.CreateRequest{Name: "A good name", Slug: "wtf-is-this-slug", MetaDescription: "a meta decription"}
	ctx, claims := auth.AuthAsAdmin(context.Background())

	res, err := s.Create(ctx, &a)

	assert.NoError(t, err)
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
	assert.Equal(t, a.Name, res.Name)
	assert.Equal(t, a.Slug, res.Slug)
	assert.Equal(t, claims.Sub, res.AuthorId)
}

func TestShouldNotBeAbleToCreateAnArticleWithSameSlug(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	tx.DB.Create(&a)
	dup := articlesgrpc.CreateRequest{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	ctx, _ := auth.AuthAsAdmin(context.Background())

	tx.DB.SavePoint("sp1")
	_, err = s.Create(ctx, &dup)
	tx.DB.RollbackTo("sp1") // rollback before failed transaction to be able to query it

	assert.Error(t, err)
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToSaveArticleWithEmptyName(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := articlesgrpc.CreateRequest{Name: ""}
	ctx, _ := auth.AuthAsAdmin(context.Background())

	_, err = s.Create(ctx, &a)

	assert.Error(t, err)
	firstValidationError := err.(articlesgrpc.CreateRequestMultiError)[0].(articlesgrpc.CreateRequestValidationError)
	assert.Equal(t, "Name", firstValidationError.Field())
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveArticleWithTooLongName(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := articlesgrpc.CreateRequest{Name: "a very too long big enormous title that will never fit in any screen..."}
	ctx, _ := auth.AuthAsAdmin(context.Background())

	_, err = s.Create(ctx, &a)

	assert.Error(t, err)
	firstValidationError := err.(articlesgrpc.CreateRequestMultiError)[0].(articlesgrpc.CreateRequestValidationError)
	assert.Equal(t, "Name", firstValidationError.Field())
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveArticleWithEmptyMetaDescription(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := articlesgrpc.CreateRequest{Name: "a good name", MetaDescription: ""}
	ctx, _ := auth.AuthAsAdmin(context.Background())

	_, err = s.Create(ctx, &a)

	assert.Error(t, err)
	firstValidationError := err.(articlesgrpc.CreateRequestMultiError)[0].(articlesgrpc.CreateRequestValidationError)
	assert.Equal(t, "MetaDescription", firstValidationError.Field())
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveArticleWithTooLongMetaDescription(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := articlesgrpc.CreateRequest{Name: "A good name", MetaDescription: "a very too long big enormous title that will never fit in any screen...a very too long big enormous title that will never fit in any screen...a very too long big enormous title that will never fit in any screen..."}
	ctx, _ := auth.AuthAsAdmin(context.Background())

	_, err = s.Create(ctx, &a)

	assert.Error(t, err)
	firstValidationError := err.(articlesgrpc.CreateRequestMultiError)[0].(articlesgrpc.CreateRequestValidationError)
	assert.Equal(t, "MetaDescription", firstValidationError.Field())
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToCreateAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := articlesgrpc.CreateRequest{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	ctx, _ := auth.AuthAsUser(context.Background())

	_, err = s.Create(ctx, &a)

	assert.Error(t, err)
	assert.ErrorAs(t, err, &article.ErrNotAdmin)
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToCreateAsGuest(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := articlesgrpc.CreateRequest{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}

	_, err = s.Create(context.Background(), &a)

	assert.Error(t, err)
	assert.Equal(t, article.ErrNoAuth, err)
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 0, int(total))
}

//////// UPDATE //////////

func TestShouldBeAbleToUpdateArticleNameAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	ctx, _ := auth.AuthAsAdmin(context.Background())

	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	a.Name = "A new name"
	u := articlesgrpc.UpdateRequest{Id: a.ID, Name: a.Name, Slug: a.Slug, MetaDescription: a.MetaDescription}
	updated, err := s.Update(ctx, &u)

	assert.NoError(t, err)
	assert.Equal(t, "A new name", updated.Name)
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateArticleTooShortNameAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	tx.DB.Create(&a)

	u := &articlesgrpc.UpdateRequest{Name: "", Slug: a.Slug, MetaDescription: a.MetaDescription}
	_, err = s.Update(context.Background(), u)

	assert.Error(t, err)
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateArticleAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	ctx, _ := auth.AuthAsUser(context.Background())
	tx.DB.Create(&a)

	u := articlesgrpc.UpdateRequest{Name: a.Name, Slug: a.Slug, MetaDescription: a.MetaDescription}
	_, err = s.Update(ctx, &u)

	assert.Error(t, err)
	assert.ErrorAs(t, err, &article.ErrNotAdmin)
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateArticleAsGuest(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	tx.DB.Create(&a)

	u := articlesgrpc.UpdateRequest{Name: a.Name, Slug: a.Slug, MetaDescription: a.MetaDescription}
	_, err = s.Update(context.Background(), &u)

	assert.Error(t, err)
	assert.ErrorAs(t, err, &article.ErrNoAuth)
	var total int64
	tx.DB.Model(&model.Article{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

//////// DELETE //////////

func TestShouldBeAbleToDeleteArticleAndIsSoftDeletedAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := model.Article{Name: "A good name", Slug: "a-good-slug", MetaDescription: "a meta decription"}
	tx.DB.Create(&a)

	ctx, _ := auth.AuthAsAdmin(context.Background())
	_, err = s.Delete(ctx, &articlesgrpc.DeleteRequest{Id: int32(a.ID)})

	var total, totalScopeless int64
	tx.DB.Model(&a).Count(&total)
	tx.DB.Model(&a).Unscoped().Count(&totalScopeless)
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
	assert.Equal(t, 1, int(totalScopeless))
}

func TestShouldNotBeAbleToDeleteAnNonExistentArticleAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)
	ctx, _ := auth.AuthAsAdmin(context.Background())

	_, err = s.Delete(ctx, &articlesgrpc.DeleteRequest{Id: 123123123})

	assert.Error(t, err)
	assert.ErrorAs(t, err, &article.ErrNotFound)
}

func TestUserShouldNotBeAbleToDelete(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	ctx, _ := auth.AuthAsUser(context.Background())
	slug := "a-good-slug"
	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Article{
		Name:            "A good Title",
		Slug:            slug,
		MetaDescription: "a meta decription",
		AuthorUUID:      id,
	}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	_, err = s.Delete(ctx, &articlesgrpc.DeleteRequest{Id: int32(a.ID)})

	assert.Error(t, err)
	assert.ErrorAs(t, err, &article.ErrNotAdmin)
}

func TestGuestShouldNotBeAbleToDelete(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewArticleRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s := article.NewService(repo)

	a := model.Article{
		Name:            "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		AuthorUUID:      "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11",
	}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	_, err = s.Delete(context.Background(), &articlesgrpc.DeleteRequest{Id: int32(a.ID)})

	assert.Error(t, err)
	assert.ErrorAs(t, err, &article.ErrNoAuth)
}
