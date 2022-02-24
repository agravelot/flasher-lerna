// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.

package model

import (
	"time"
)

const TableNameSocialMedium = "social_media"

// SocialMedium mapped from table <social_media>
type SocialMedium struct {
	ID        string     `gorm:"column:id;type:;primaryKey;autoIncrement:true" json:"id"`
	Name      string     `gorm:"column:name;type:;not null" json:"name"`
	URL       string     `gorm:"column:url;type:;not null" json:"url"`
	Icon      string     `gorm:"column:icon;type:;not null" json:"icon"`
	Color     string     `gorm:"column:color;type:;not null" json:"color"`
	Active    string     `gorm:"column:active;type:;not null" json:"active"`
	CreatedAt *time.Time `gorm:"column:created_at;type:" json:"created_at"`
	UpdatedAt *time.Time `gorm:"column:updated_at;type:" json:"updated_at"`
}

// TableName SocialMedium's table name
func (*SocialMedium) TableName() string {
	return TableNameSocialMedium
}
