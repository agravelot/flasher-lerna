package article

import (
	"time"

	"gorm.io/gorm"
)

// Article represents a single  article.
// ID should be globally unique.
type Article struct {
	ID        uint           `gorm:"primarykey" json:"id"`
	Slug      string         `json:"slug"`
	Name      string         `json:"name"`
	Content   string         `json:"content"`
	CreatedAt time.Time      `json:"created_at"`
	UpdatedAt time.Time      `json:"updated_at"`
	DeletedAt gorm.DeletedAt `gorm:"index" json:"-"`
}
