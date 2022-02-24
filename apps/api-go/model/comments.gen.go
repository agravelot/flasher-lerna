// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.

package model

import (
	"time"
)

const TableNameComment = "comments"

// Comment mapped from table <comments>
type Comment struct {
	ID              string     `gorm:"column:id;type:;primaryKey;autoIncrement:true" json:"id"`
	CreatedAt       *time.Time `gorm:"column:created_at;type:" json:"created_at"`
	UpdatedAt       *time.Time `gorm:"column:updated_at;type:" json:"updated_at"`
	UserID          *string    `gorm:"column:user_id;type:" json:"user_id"`
	CommentableID   string     `gorm:"column:commentable_id;type:;not null" json:"commentable_id"`
	CommentableType string     `gorm:"column:commentable_type;type:;not null" json:"commentable_type"`
	Body            string     `gorm:"column:body;type:;not null" json:"body"`
	SsoID           string     `gorm:"column:sso_id;type:;not null" json:"sso_id"`
}

// TableName Comment's table name
func (*Comment) TableName() string {
	return TableNameComment
}
