package albums

import (
	"api-go/categories"
	"database/sql"
	"time"

	"github.com/guregu/null"
	uuid "github.com/satori/go.uuid"
	"gorm.io/gorm"
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


Table: albums
[ 0] id                                             INT4                 null: false  primary: true   isArray: false  auto: false  col: INT4            len: -1      default: []
[ 1] slug                                           VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 2] title                                          VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[ 3] body                                           TEXT                 null: true   primary: false  isArray: false  auto: false  col: TEXT            len: -1      default: []
[ 4] published_at                                   TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 5] private                                        BOOL                 null: false  primary: false  isArray: false  auto: false  col: BOOL            len: -1      default: [true]
[ 6] user_id                                        INT8                 null: true   primary: false  isArray: false  auto: false  col: INT8            len: -1      default: []
[ 7] created_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 8] updated_at                                     TIMESTAMP            null: true   primary: false  isArray: false  auto: false  col: TIMESTAMP       len: -1      default: []
[ 9] notify_users_on_published                      BOOL                 null: false  primary: false  isArray: false  auto: false  col: BOOL            len: -1      default: [true]
[10] meta_description                               VARCHAR(255)         null: false  primary: false  isArray: false  auto: false  col: VARCHAR         len: 255     default: []
[11] sso_id                                         UUID                 null: true   primary: false  isArray: false  auto: false  col: UUID            len: -1      default: []


JSON Sample
-------------------------------------
{    "id": 71,    "slug": "iowlFWASrYdbLbcLvZlPvAWaF",    "title": "iqvAUBdKkSQoILtuaLhhKfStP",    "body": "nwgQSwDJppqYYZJSmgNvFwYHo",    "published_at": "2165-09-29T21:46:19.854176159+02:00",    "private": false,    "user_id": 39,    "created_at": "2108-10-09T22:35:34.616288759+02:00",    "updated_at": "2121-12-03T03:05:18.49078874+01:00",    "notify_users_on_published": false,    "meta_description": "PBhxYuSHQkKLfNaAbkaOJapii",    "sso_id": "wIBoMTqscYHLqCtEObNShiyUF"}
*/

// Album struct is a row record of the albums table in the flasher database
type Album struct {
	ID                     uint                   `gorm:"primaryKey;autoIncrement" json:"id" example:"1"`
	Slug                   string                 `gorm:"type:VARCHAR;size:255;uniqueIndex" json:"slug" example:"a-good-album"`
	Title                  string                 `gorm:"type:VARCHAR;size:255;" json:"title" example:"A good album"`
	Body                   null.String            `gorm:"type:TEXT;" json:"body" swaggertype:"string" example:"<p>Hello world</p>"`
	PublishedAt            null.Time              `gorm:"type:TIMESTAMP;" json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	Private                bool                   `gorm:"type:BOOL;default:true;" json:"private" example:"true"`
	UserID                 null.Int               `gorm:"type:INT8;" json:"user_id" swaggertype:"number" example:"1"`
	CreatedAt              null.Time              `gorm:"type:TIMESTAMP;" json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt              null.Time              `gorm:"type:TIMESTAMP;" json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	NotifyUsersOnPublished bool                   `gorm:"type:BOOL;default:true;" json:"notify_users_on_published" example:"true"`
	MetaDescription        string                 `gorm:"type:VARCHAR;size:255;" json:"meta_description" example:"A good meta"`
	SsoID                  null.String            `gorm:"type:UUID;" json:"sso_id" swaggertype:"string" example:"123e4567-e89b-12d3-a456-426614174000"`
	Categories             []*categories.Category `gorm:"many2many:album_category;"`
}

// TableName sets the insert table name for this struct type
func (a *Album) TableName() string {
	return "albums"
}

// BeforeSave invoked before saving, return an error if field is not populated.
func (a *Album) BeforeSave(*gorm.DB) error {
	return nil
}

// Prepare invoked before saving, can be used to populate fields etc.
func (a *Album) Prepare() {
}
