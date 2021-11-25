package albums

import (
	"api-go/api"
	database "api-go/db"

	"gorm.io/gorm"
)

// PaginatedAlbums Paginated albums
type PaginatedAlbums struct {
	Data []Album  `json:"data"`
	Meta api.Meta `json:"meta"`
}

// GetAlbumBySlug godoc
func GetAlbumBySlug(slug string) (Album, error) {
	db := database.DbManager()
	album := Album{}
	err := db.Where("slug = ?", slug).Scopes(Public).First(&album).Error

	return album, err
}

func GetAlbumsPaginated(next string, limit int) PaginatedAlbums {
	dbInstance := database.DbManager()

	albums := []Album{}
	dbInstance.Scopes(api.Paginate(next, limit), Public).Find(&albums)

	var total int64
	dbInstance.Model(&albums).Scopes(api.Paginate(next, limit), Public).Count(&total)

	return PaginatedAlbums{
		Data: albums,
		Meta: api.Meta{Total: total, Limit: limit},
	}
}

func Public(db *gorm.DB) *gorm.DB {
	return db.Where("private = false").Where("published_at is not null")
}
