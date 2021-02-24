package article

import (
	"time"

	"github.com/gosimple/slug"
	"gorm.io/gorm"
)

// Article represents a single  article.
// ID should be globally unique.
type Article struct {
	ID        uint           `gorm:"primarykey" json:"id"`
	Slug      string         `json:"slug" gorm:"uniqueIndex"`
	Name      string         `json:"name"`
	Content   string         `json:"content"`
	CreatedAt time.Time      `json:"created_at"`
	UpdatedAt time.Time      `json:"updated_at"`
	DeletedAt gorm.DeletedAt `gorm:"index" json:"-"`
}

func (a *Article) BeforeCreate(tx *gorm.DB) (err error) {
	if a.Slug == "" {
		a.Slug = slug.Make(a.Name)
	}
	return
}
