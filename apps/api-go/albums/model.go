package album

import (
	"time"

	"github.com/gosimple/slug"
	"github.com/guregu/null"
	"gorm.io/gorm"
)

// AlbumModel represents a single album.
type AlbumModel struct {
	ID                     uint      `gorm:"type:bigserial;primaryKey;autoIncrement"`
	Slug                   string    `gorm:"type:VARCHAR;size:255;uniqueIndex"`
	Title                  string    `gorm:"type:VARCHAR;size:255;"`
	Body                   string    `gorm:"type:TEXT;"`
	PublishedAt            null.Time `gorm:"type:TIMESTAMP;"`
	Private                *bool     `gorm:"type:BOOL;default:true;"`
	NotifyUsersOnPublished *bool     `gorm:"type:BOOL;default:true;"`
	MetaDescription        string    `gorm:"type:VARCHAR;size:255;"`
	SsoID                  string    `gorm:"type:UUID;"`
	CreatedAt              time.Time `gorm:"type:TIMESTAMP;"`
	UpdatedAt              null.Time `gorm:"type:TIMESTAMP;"`

	Categories *[]CategoryModel `gorm:"many2many:album_category;joinForeignKey:AlbumID;joinReferences:CategoryID"`
	Medias     *[]MediaModel    `gorm:"polymorphic:Model;polymorphicValue:App\\Models\\Album"`
}

func (AlbumModel) TableName() string {
	return "albums"
}

func (a *AlbumModel) BeforeCreate(tx *gorm.DB) (err error) {
	if a.Slug == "" {
		a.Slug = slug.Make(a.Title)
	}
	return
}
