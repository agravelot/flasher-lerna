package api

import (
	"api-go/gormQuery"

	"gorm.io/gen"
)

type MetaOld struct {
	Total int64 `json:"total"`
	Limit int   `json:"limit"`
}

type Meta struct {
	Total int64 `json:"total"`
	Limit int32 `json:"limit"`
}

type Paginated struct {
	Data interface{} `json:"data"`
	Meta Meta        `json:"meta"`
}

func Paginate(q *gormQuery.Query, next int32, pageSize int) func(db gen.Dao) gen.Dao {
	return func(db gen.Dao) gen.Dao {
		if next != 0 {
			db = db.Where(q.Album.ID.Gt(next))
		}
		return db.Limit(pageSize)
	}
}
