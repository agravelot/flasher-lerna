package article

import (
	"time"

	"github.com/go-playground/validator/v10"
)

// use a single instance of Validate, it caches struct info
var validate *validator.Validate

type ArticleRequest struct {
	ID              int64  `gorm:"primarykey" json:"id"`
	Slug            string `json:"slug" gorm:"uniqueIndex"`
	Name            string `json:"name" validate:"required,lt=60"`
	MetaDescription string `json:"meta_description" validate:"required,gt=1,lt=60"`
	Content         string `json:"content"`
	// AuthorUUID      string     `json:"author_uuid" validate:"required,uuid"`
	PublishedAt *time.Time `json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z" ts:"date,null"`

	// Categories *[]CategoryModel `json:"categories"`
	// Medias     *[]MediaModel    `json:"medias"`
}

func (a *ArticleRequest) Validate() error {
	validate = validator.New()

	err := validate.Struct(a)

	return err
}

type ArticleResponse struct {
	ID              int64  `gorm:"primarykey" json:"id"`
	Slug            string `json:"slug" gorm:"uniqueIndex"`
	Name            string `json:"name"`
	MetaDescription string `json:"meta_description"`
	Content         string `json:"content"`
	// AuthorUUID      string     `json:"author_uuid"`
	PublishedAt *time.Time `json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z" ts:"date,null"`

	// Categories *[]CategoryModel `json:"categories"`
	// Medias     *[]MediaModel    `json:"medias"`
}
