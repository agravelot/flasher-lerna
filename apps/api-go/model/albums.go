package model

import (
	"time"

	"gorm.io/gorm"
)

func (a *Album) BeforeCreate(tx *gorm.DB) (err error) {
	// t := a.Private
	// a.IsPublishedPublicly = *t
	return
}

type PartialAlbum struct {
	ID                     *int32     `gorm:"column:id;type:integer;primaryKey;autoIncrement:true" json:"id"`
	Slug                   *string    `gorm:"column:slug;type:character varying(255);not null;uniqueIndex:albums_slug_unique,priority:1" json:"slug"`
	Title                  *string    `gorm:"column:title;type:character varying(255);not null;uniqueIndex:albums_title_unique,priority:1" json:"title"`
	Body                   *string    `gorm:"column:body;type:text" json:"body"`
	PublishedAt            *time.Time `gorm:"column:published_at;type:timestamp(0) without time zone;index:albums_published_at_private_index,priority:1" json:"published_at"`
	Private                *bool      `gorm:"column:private;type:boolean;not null;index:albums_published_at_private_index,priority:2" json:"private"`
	UserID                 *int64     `gorm:"column:user_id;type:bigint" json:"user_id"`
	CreatedAt              *time.Time `gorm:"column:created_at;type:timestamp(0) without time zone" json:"created_at"`
	UpdatedAt              *time.Time `gorm:"column:updated_at;type:timestamp(0) without time zone" json:"updated_at"`
	NotifyUsersOnPublished *bool      `gorm:"column:notify_users_on_published;type:boolean;not null;default:true" json:"notify_users_on_published"`
	MetaDescription        *string    `gorm:"column:meta_description;type:character varying(255);not null" json:"meta_description"`
	SsoID                  *string    `gorm:"column:sso_id;type:uuid" json:"sso_id"`
	// Categories             []Category `gorm:"many2many:album_category;joinForeignKey:AlbumID;joinReferences:CategoryID" json:"categories"`
	// Medias                 []Medium   `gorm:"polymorphic:Model;polymorphicValue:App\\Models\\Album" json:"medias"`
}

func (*PartialAlbum) TableName() string {
	return TableNameAlbum
}
