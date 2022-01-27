package album

import (
	"database/sql"
	"time"

	"github.com/go-playground/validator/v10"
	"github.com/google/uuid"
)

// AlbumModel represents a single album.
type AlbumRequest struct {
	ID                     int32      `json:"id" example:"1"`
	Slug                   string     `json:"slug" example:"a-good-album"`
	Title                  string     `json:"title" example:"A good album" validate:"required,lt=60"`
	Body                   *string    `json:"body" swaggertype:"string" example:"<p>Hello world</p>"`
	PublishedAt            *time.Time `json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	Private                bool       `json:"private" example:"true"`
	UserID                 *int64     `json:"-"`
	CreatedAt              *time.Time `json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt              *time.Time `json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	NotifyUsersOnPublished bool       `json:"notify_users_on_published" example:"true"`
	MetaDescription        string     `json:"meta_description" example:"A good meta" validate:"required,gt=1,lt=60"`
	SsoID                  *uuid.UUID `json:"sso_id" swaggertype:"string" example:"123e4567-e89b-12d3-a456-426614174000"`

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

type MediaReponse struct {
	ID             int32  `json:"id" example:"1"`
	Name           string `json:"name" example:"A good media"`
	ModelType      string
	ModelID        int64
	CollectionName string
	FileName       string
	MimeType       sql.NullString
	Disk           string
	Size           int64
	// Manipulations    pgtype.JSON
	// CustomProperties pgtype.JSON
	// ResponsiveImages pgtype.JSON
	OrderColumn     sql.NullInt32
	CreatedAt       sql.NullTime
	UpdatedAt       sql.NullTime
	Uuid            uuid.NullUUID
	ConversionsDisk sql.NullString
}

type AlbumResponse struct {
	ID                     int32      `json:"id" example:"1"`
	Slug                   string     `json:"slug" example:"a-good-album"`
	Title                  string     `json:"title" example:"A good album" validate:"required,lt=60"`
	Body                   *string    `json:"body" swaggertype:"string" example:"<p>Hello world</p>"`
	PublishedAt            *time.Time `json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	Private                bool       `json:"private" example:"true"`
	UserID                 *int64     `json:"-"`
	CreatedAt              *time.Time `json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt              *time.Time `json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	NotifyUsersOnPublished bool       `json:"notify_users_on_published" example:"true"`
	MetaDescription        string     `json:"meta_description" example:"A good meta" validate:"required,gt=1,lt=60"`
	SsoID                  uuid.UUID  `json:"sso_id" swaggertype:"string" example:"123e4567-e89b-12d3-a456-426614174000"`

	Categories *[]CategoryReponse `json:"categories"`
	Medias     *[]MediaReponse    `json:"medias"`
}
