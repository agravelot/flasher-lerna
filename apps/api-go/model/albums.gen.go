// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.

package model

import (
	"time"
)

const TableNameAlbum = "albums"

// Album mapped from table <albums>
type Album struct {
	ID                     int64      `gorm:"column:id;type:int4;primaryKey;autoIncrement:true" json:"id"`
	Slug                   string     `gorm:"column:slug;type:varchar;not null" json:"slug"`
	Title                  string     `gorm:"column:title;type:varchar;not null" json:"title"`
	Body                   *string    `gorm:"column:body;type:text" json:"body"`
	PublishedAt            *time.Time `gorm:"column:published_at;type:timestamptz" json:"published_at"`
	Private                bool       `gorm:"column:private;type:bool;not null;default:true" json:"private"`
	UserID                 *int64     `gorm:"column:user_id;type:int8" json:"user_id"`
	CreatedAt              *time.Time `gorm:"column:created_at;type:timestamptz" json:"created_at"`
	UpdatedAt              *time.Time `gorm:"column:updated_at;type:timestamptz" json:"updated_at"`
	NotifyUsersOnPublished bool       `gorm:"column:notify_users_on_published;type:bool;not null;default:true" json:"notify_users_on_published"`
	MetaDescription        string     `gorm:"column:meta_description;type:varchar;not null" json:"meta_description"`
	SsoID                  *string    `gorm:"column:sso_id;type:uuid" json:"sso_id"`
	Categories             []Category `gorm:"many2many:album_category;joinForeignKey:AlbumID;joinReferences:CategoryID" json:"categories"`
	Medias                 []Medium   `gorm:"polymorphic:Model;polymorphicValue:App\\Models\\Album" json:"medias"`
}

// TableName Album's table name
func (*Album) TableName() string {
	return TableNameAlbum
}
