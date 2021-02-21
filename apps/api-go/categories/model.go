package categories

import (
	"api-go/albums"
	"database/sql"
	"time"

	"github.com/guregu/null"
	uuid "github.com/satori/go.uuid"
)

var (
	_ = time.Second
	_ = sql.LevelDefault
	_ = null.Bool{}
	_ = uuid.UUID{}
)

/*
DB Table Details
-------------------------------------


Table: categories
[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
[ 1] name                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 2] slug                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 3] description                                    TEXT                 null: true   primary: false  isArray: false  auto: false  col: TEXT            len: -1      default: []
[ 4] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 5] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 6] meta_description                               VARCHAR(155)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 155     default: []


JSON Sample
-------------------------------------
{    "id": 44,    "name": "skFylRVRBkhTwhqkNMGNmORfn",    "slug": "giTHmCWMFVHmRfeYurqrkOvTV",    "description": "VFQqKVCMVinwQGDEnKnUBLHuh",    "created_at": "2225-05-25T08:40:06.389879055+02:00",    "updated_at": "2168-10-28T13:36:34.996816628+02:00",    "meta_description": "ntEKoqwDfotilTMxEmmvuaqBG"}



*/

// Category struct is a row record of the categories table in the flasher database
type Category struct {
	ID              uint            `gorm:"primaryKey;autoIncrement" json:"id" example:"1"`
	Name            string          `gorm:"column:name;type:VARCHAR;size:255;" json:"name"`
	Slug            string          `gorm:"column:slug;type:VARCHAR;size:255;" json:"slug"`
	Description     null.String     `gorm:"column:description;type:TEXT;" json:"description"`
	CreatedAt       null.Time       `gorm:"column:created_at;type:TIMESTAMP;" json:"created_at"`
	UpdatedAt       null.Time       `gorm:"column:updated_at;type:TIMESTAMP;" json:"updated_at"`
	MetaDescription string          `gorm:"column:meta_description;type:VARCHAR;size:155;" json:"meta_description"`
	Albums          []*albums.Album `gorm:"many2many:album_category;"`
}

// TableName sets the insert table name for this struct type
func (c *Category) TableName() string {
	return "categories"
}

// BeforeSave invoked before saving, return an error if field is not populated.
func (c *Category) BeforeSave() error {
	return nil
}

// Prepare invoked before saving, can be used to populate fields etc.
func (c *Category) Prepare() {
}
