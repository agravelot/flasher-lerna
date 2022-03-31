package model

import (
	"gorm.io/gorm"
)

func (a *Album) BeforeCreate(tx *gorm.DB) (err error) {
	a.IsPublishedPublicly = !a.Private
	return
}
