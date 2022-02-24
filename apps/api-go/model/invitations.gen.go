// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.
// Code generated by gorm.io/gen. DO NOT EDIT.

package model

import (
	"time"
)

const TableNameInvitation = "invitations"

// Invitation mapped from table <invitations>
type Invitation struct {
	OldID       string     `gorm:"column:old_id;type:;not null" json:"old_id"`
	CosplayerID string     `gorm:"column:cosplayer_id;type:;not null" json:"cosplayer_id"`
	Email       string     `gorm:"column:email;type:;not null" json:"email"`
	Message     *string    `gorm:"column:message;type:" json:"message"`
	CreatedAt   *time.Time `gorm:"column:created_at;type:" json:"created_at"`
	UpdatedAt   *time.Time `gorm:"column:updated_at;type:" json:"updated_at"`
	Token       string     `gorm:"column:token;type:;not null" json:"token"`
	ConfirmedAt *time.Time `gorm:"column:confirmed_at;type:" json:"confirmed_at"`
	ID          string     `gorm:"column:id;type:;primaryKey" json:"id"`
}

// TableName Invitation's table name
func (*Invitation) TableName() string {
	return TableNameInvitation
}
