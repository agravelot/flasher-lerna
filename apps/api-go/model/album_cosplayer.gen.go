// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.

package model

import (
	"time"
)

const TableNameAlbumCosplayer = "album_cosplayer"

// AlbumCosplayer mapped from table <album_cosplayer>
type AlbumCosplayer struct {
	ID          string     `gorm:"column:id;type:;primaryKey;autoIncrement:true" json:"id"`
	AlbumID     *string    `gorm:"column:album_id;type:" json:"album_id"`
	CosplayerID *string    `gorm:"column:cosplayer_id;type:" json:"cosplayer_id"`
	CreatedAt   *time.Time `gorm:"column:created_at;type:" json:"created_at"`
	UpdatedAt   *time.Time `gorm:"column:updated_at;type:" json:"updated_at"`
}

// TableName AlbumCosplayer's table name
func (*AlbumCosplayer) TableName() string {
	return TableNameAlbumCosplayer
}
