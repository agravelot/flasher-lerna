package album

import (
	"time"

	"github.com/go-playground/validator/v10"
	"github.com/gosimple/slug"
	"github.com/guregu/null"
	"gorm.io/gorm"
)

// Album represents a single album.
// ID should be globally unique.
type Album struct {
	ID                     uint      `gorm:"primaryKey;autoIncrement" json:"id" example:"1"`
	Slug                   string    `gorm:"type:VARCHAR;size:255;uniqueIndex" json:"slug" example:"a-good-album"`
	Title                  string    `gorm:"type:VARCHAR;size:255;" json:"title" example:"A good album" validate:"required,lt=60"`
	Body                   string    `gorm:"type:TEXT;" json:"body" swaggertype:"string" example:"<p>Hello world</p>"`
	PublishedAt            null.Time `gorm:"type:TIMESTAMP;" json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	Private                bool      `gorm:"type:BOOL;default:true;" json:"private" example:"true"`
	NotifyUsersOnPublished bool      `gorm:"type:BOOL;default:true;" json:"notify_users_on_published" example:"true"`
	MetaDescription        string    `gorm:"type:VARCHAR;size:255;" json:"meta_description" example:"A good meta" validate:"required,gt=1,lt=60"`
	SsoID                  string    `gorm:"type:UUID;" json:"sso_id" swaggertype:"string" example:"123e4567-e89b-12d3-a456-426614174000"`
	CreatedAt              time.Time `gorm:"type:TIMESTAMP;" json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt              null.Time `gorm:"type:TIMESTAMP;" json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
}

func (a *Album) BeforeCreate(tx *gorm.DB) (err error) {
	if a.Slug == "" {
		a.Slug = slug.Make(a.Title)
	}
	return
}

// use a single instance of Validate, it caches struct info
var validate *validator.Validate

func (a *Album) Validate() error {
	validate = validator.New()

	err := validate.Struct(a)

	return err
}
