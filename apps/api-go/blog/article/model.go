package article

import (
	"time"

	"github.com/gosimple/slug"
	"github.com/guregu/null"
	"gorm.io/gorm"
)

// Article represents a single  article.
// ID should be globally unique.
type Article struct {
	ID              uint           `gorm:"primarykey" json:"id"`
	Slug            string         `json:"slug" gorm:"uniqueIndex"`
	Name            string         `json:"name"`
	MetaDescription string         `json:"meta_description"`
	Content         string         `json:"content"`
	AuthorUUID      string         `json:"author_uuid"`
	PublishedAt     null.Time      `gorm:"type:TIMESTAMP;" json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	CreatedAt       time.Time      `json:"created_at"`
	UpdatedAt       time.Time      `json:"updated_at"`
	DeletedAt       gorm.DeletedAt `gorm:"index" json:"-"`
}

func (a *Article) BeforeCreate(tx *gorm.DB) (err error) {
	if a.Slug == "" {
		a.Slug = slug.Make(a.Name)
	}
	return
}
