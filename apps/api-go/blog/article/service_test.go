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
