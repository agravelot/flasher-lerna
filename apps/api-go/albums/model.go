package album

import (
	"database/sql/driver"
	"encoding/json"
	"errors"
	"fmt"
	"time"

	"github.com/go-playground/validator/v10"
	"github.com/gosimple/slug"
	"github.com/guregu/null"
	"github.com/lib/pq"
	"gorm.io/gorm"
)

type ResponsiveImages struct {
	Responsive Responsive `gorm:"" json:"responsive"`
}

func (ri ResponsiveImages) Value() (driver.Value, error) {
	return nil, nil
}

func (ri *ResponsiveImages) Scan(value interface{}) error {
	bytes, ok := value.([]byte)
	if !ok {
		return errors.New(fmt.Sprint("Failed to unmarshal JSONB value:", value))
	}

	result := ResponsiveImages{}
	err := json.Unmarshal(bytes, &result)
	*ri = result
	return err
}

type Responsive struct {
	Urls      pq.StringArray `gorm:"type:text[];" json:"urls"`
	Base64Svg string         `json:"base64svg"`
}

type CustomProperties struct {
	GeneratedConversions GeneratedConversions `json:"generated_conversions"`
	Width                int                  `json:"width"`
	Height               int                  `json:"height"`
}

func (ri CustomProperties) Value() (driver.Value, error) {
	return nil, nil
}

func (ri *CustomProperties) Scan(value interface{}) error {
	bytes, ok := value.([]byte)
	if !ok {
		return errors.New(fmt.Sprint("Failed to unmarshal JSONB value:", value))
	}

	result := CustomProperties{}
	err := json.Unmarshal(bytes, &result)
	*ri = result
	return err
}

type GeneratedConversions struct {
	Thumb      bool `json:"thumb"`
	Responsive bool `json:"responsive"`
}

type Media struct {
	ID               uint              `gorm:"primaryKey,autoIncrement" json:"id"`
	ModelType        string            `gorm:"column:model_type;type:VARCHAR;size:255;" json:"model_type"`
	ModelID          uint              `gorm:"column:model_id;type:INT8;" json:"model_id"`
	CollectionName   string            `gorm:"column:collection_name;type:VARCHAR;size:255;" json:"collection_name"`
	Name             string            `gorm:"column:name;type:VARCHAR;size:255;" json:"name"`
	FileName         string            `gorm:"column:file_name;type:VARCHAR;size:255;" json:"file_name"`
	MimeType         null.String       `gorm:"column:mime_type;type:VARCHAR;size:255;" json:"mime_type"`
	Disk             string            `gorm:"column:disk;type:VARCHAR;size:255;" json:"disk"`
	Size             int64             `gorm:"column:size;type:INT8;" json:"size"`
	Manipulations    string            `gorm:"column:manipulations;type:JSON;" json:"manipulations"`
	CustomProperties *CustomProperties `gorm:"type:json;" json:"custom_properties"`
	ResponsiveImages *ResponsiveImages `gorm:"type:json;" json:"responsive_images"`
	OrderColumn      null.Int          `gorm:"column:order_column;type:INT4;" json:"order_column"`
	CreatedAt        null.Time         `gorm:"column:created_at;type:TIMESTAMP;" json:"created_at"`
	UpdatedAt        null.Time         `gorm:"column:updated_at;type:TIMESTAMP;" json:"updated_at"`
	UUID             null.String       `gorm:"column:uuid;type:UUID;" json:"uuid"`
	ConversionsDisk  null.String       `gorm:"column:conversions_disk;type:VARCHAR;size:255;" json:"conversions_disk"`
}

func (Media) TableName() string {
	return "media"
}

type Category struct {
	ID              int         `gorm:"type:bigint;primaryKey;autoIncrement" json:"id"`
	Name            string      `gorm:"column:name;type:VARCHAR;size:255;" json:"name"`
	Slug            string      `gorm:"column:slug;type:VARCHAR;size:255;" json:"slug"`
	Description     null.String `gorm:"column:description;type:TEXT;" json:"description"`
	MetaDescription string      `gorm:"column:meta_description;type:VARCHAR;size:155;" json:"meta_description"`
	CreatedAt       time.Time   `gorm:"type:TIMESTAMP;" json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt       null.Time   `gorm:"type:TIMESTAMP;" json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`

	Albums []*Album `gorm:"many2many:album_category" json:"albums"`
}

type AlbumCategory struct {
	AlbumID    int       `gorm:"primaryKey"`
	CategoryID int       `gorm:"primaryKey"`
	CreatedAt  time.Time `gorm:"type:TIMESTAMP;" json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt  null.Time `gorm:"type:TIMESTAMP;" json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
}

func (AlbumCategory) TableName() string {
	return "album_category"
}

// Album represents a single album.
// ID should be globally unique.
type Album struct {
	ID                     uint      `gorm:"type:bigint;primaryKey;autoIncrement" json:"id" example:"1"`
	Slug                   string    `gorm:"type:VARCHAR;size:255;uniqueIndex" json:"slug" example:"a-good-album"`
	Title                  string    `gorm:"type:VARCHAR;size:255;" json:"title" example:"A good album" validate:"required,lt=60"`
	Body                   string    `gorm:"type:TEXT;" json:"body" swaggertype:"string" example:"<p>Hello world</p>"`
	PublishedAt            null.Time `gorm:"type:TIMESTAMP;" json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	Private                *bool     `gorm:"type:BOOL;default:true;" json:"private" example:"true"`
	NotifyUsersOnPublished bool      `gorm:"type:BOOL;default:true;" json:"notify_users_on_published" example:"true"`
	MetaDescription        string    `gorm:"type:VARCHAR;size:255;" json:"meta_description" example:"A good meta" validate:"required,gt=1,lt=60"`
	SsoID                  string    `gorm:"type:UUID;" json:"sso_id" swaggertype:"string" example:"123e4567-e89b-12d3-a456-426614174000"`
	CreatedAt              time.Time `gorm:"type:TIMESTAMP;" json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt              null.Time `gorm:"type:TIMESTAMP;" json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`

	Categories []*Category `gorm:"many2many:album_category" json:"categories"`
	Medias     []*Media    `gorm:"polymorphic:Model;polymorphicValue:App\\Models\\Album" json:"medias"`
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
