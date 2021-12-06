package album

import (
	"database/sql/driver"
	"encoding/json"
	"errors"
	"fmt"
	"time"

	"github.com/guregu/null"
	"github.com/lib/pq"
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

type MediaModel struct {
	ID               uint              `gorm:"primaryKey,autoIncrement" json:"id"`
	ModelType        string            `gorm:"column:model_type;type:VARCHAR;size:255;" json:"model_type"`
	ModelID          uint              `gorm:"column:model_id;type:INT8;" json:"model_id"`
	CollectionName   string            `gorm:"column:collection_name;type:VARCHAR;size:255;" json:"collection_name"`
	Name             string            `gorm:"column:name;type:VARCHAR;size:255;" json:"name"`
	FileName         string            `gorm:"column:file_name;type:VARCHAR;size:255;" json:"file_name"`
	MimeType         null.String       `gorm:"column:mime_type;type:VARCHAR;size:255;" json:"mime_type"`
	Disk             string            `gorm:"column:disk;type:VARCHAR;size:255;" json:"disk"`
	Size             int64             `gorm:"column:size;type:INT8;" json:"size"`
	Manipulations    *interface{}      `gorm:"column:manipulations;type:JSON;" json:"manipulations"`
	CustomProperties *CustomProperties `gorm:"type:json;" json:"custom_properties"`
	ResponsiveImages *ResponsiveImages `gorm:"type:json;" json:"responsive_images"`
	OrderColumn      null.Int          `gorm:"column:order_column;type:INT4;" json:"order_column"`
	CreatedAt        null.Time         `gorm:"column:created_at;type:TIMESTAMP;" json:"created_at"`
	UpdatedAt        null.Time         `gorm:"column:updated_at;type:TIMESTAMP;" json:"updated_at"`
	UUID             null.String       `gorm:"column:uuid;type:UUID;" json:"uuid"`
	ConversionsDisk  null.String       `gorm:"column:conversions_disk;type:VARCHAR;size:255;" json:"conversions_disk"`
}

func (MediaModel) TableName() string {
	return "media"
}

type CategoryModel struct {
	ID              uint        `gorm:"type:bigserial;primaryKey;autoIncrement" json:"id" example:"1"`
	Name            string      `gorm:"column:name;type:VARCHAR;size:255;" json:"name"`
	Slug            string      `gorm:"column:slug;type:VARCHAR;size:255;" json:"slug"`
	Description     null.String `gorm:"column:description;type:TEXT;" json:"description"`
	MetaDescription string      `gorm:"column:meta_description;type:VARCHAR;size:155;" json:"meta_description"`
	CreatedAt       time.Time   `gorm:"type:TIMESTAMP;" json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt       null.Time   `gorm:"type:TIMESTAMP;" json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`

	Albums []*AlbumModel `gorm:"many2many:album_category;joinForeignKey:CategoryID;joinReferences:AlbumID" json:"albums"`
}

func (CategoryModel) TableName() string {
	return "categories"
}

type AlbumCategoryModel struct {
	AlbumID    int       `gorm:"primaryKey"`
	CategoryID int       `gorm:"primaryKey"`
	CreatedAt  time.Time `gorm:"type:TIMESTAMP;" json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt  null.Time `gorm:"type:TIMESTAMP;" json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
}

func (AlbumCategoryModel) TableName() string {
	return "album_category"
}
