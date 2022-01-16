package album

import (
	"database/sql"

	"github.com/go-playground/validator/v10"
	"github.com/google/uuid"
)

// AlbumModel represents a single album.
type AlbumRequest struct {
	ID                     int32          `json:"id" example:"1"`
	Slug                   string         `json:"slug" example:"a-good-album"`
	Title                  string         `json:"title" example:"A good album" validate:"required,lt=60"`
	Body                   sql.NullString `json:"body" swaggertype:"string" example:"<p>Hello world</p>"`
	PublishedAt            sql.NullTime   `json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	Private                bool           `json:"private" example:"true"`
	UserID                 sql.NullInt64  `json:"-"`
	CreatedAt              sql.NullTime   `json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt              sql.NullTime   `json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	NotifyUsersOnPublished bool           `json:"notify_users_on_published" example:"true"`
	MetaDescription        string         `json:"meta_description" example:"A good meta" validate:"required,gt=1,lt=60"`
	SsoID                  uuid.NullUUID  `json:"sso_id" swaggertype:"string" example:"123e4567-e89b-12d3-a456-426614174000"`

	// Categories *[]CategoryModel `json:"categories"`
	// Medias     *[]MediaModel    `json:"medias"`
}

// use a single instance of Validate, it caches struct info
var validate *validator.Validate

func (a *AlbumRequest) Validate() error {
	validate = validator.New()

	err := validate.Struct(a)

	return err
}

type CategoryReponse struct {
	ID   int32  `json:"id" example:"1"`
	Name string `json:"name" example:"A good category"`
}

type AlbumResponse struct {
	ID                     int32          `json:"id" example:"1"`
	Slug                   string         `json:"slug" example:"a-good-album"`
	Title                  string         `json:"title" example:"A good album" validate:"required,lt=60"`
	Body                   sql.NullString `json:"body" swaggertype:"string" example:"<p>Hello world</p>"`
	PublishedAt            sql.NullTime   `json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	Private                bool           `json:"private" example:"true"`
	UserID                 sql.NullInt64  `json:"-"`
	CreatedAt              sql.NullTime   `json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt              sql.NullTime   `json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	NotifyUsersOnPublished bool           `json:"notify_users_on_published" example:"true"`
	MetaDescription        string         `json:"meta_description" example:"A good meta" validate:"required,gt=1,lt=60"`
	SsoID                  uuid.NullUUID  `json:"sso_id" swaggertype:"string" example:"123e4567-e89b-12d3-a456-426614174000"`

	Categories *[]CategoryReponse `json:"categories"`
	// Medias     *[]MediaModel    `json:"medias"`
}
