package model

import (
	"github.com/gosimple/slug"
	"gorm.io/gorm"
)

func (a *Article) BeforeCreate(tx *gorm.DB) (err error) {
	if a.Slug == "" {
		a.Slug = slug.Make(a.Name)
	}
	return
}
