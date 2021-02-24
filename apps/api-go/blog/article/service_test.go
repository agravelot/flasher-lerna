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

func TestPostArticle(t *testing.T) {
	t.Run("should be able to create an article and generate slug", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name"}

		res, err := s.PostArticle(context.Background(), a)

		assert.NoError(t, err)
		var total int64
		db.Model(&Article{}).Count(&total)
		assert.Equal(t, 1, int(total))
		assert.Equal(t, a.Name, res.Name)
		assert.Equal(t, "a-good-name", res.Slug)
	})

	t.Run("should be able to create an article with a specified slug", func(t *testing.T) {
		database.ClearDB(db)
		a := Article{Name: "A good name", Slug: "wtf-is-this-slug"}

		res, err := s.PostArticle(context.Background(), a)

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

		res, err := s.PostArticle(context.Background(), dup)

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
