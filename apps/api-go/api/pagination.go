package api

import (
	"strconv"

	"github.com/labstack/echo/v4"
	"gorm.io/gorm"
)

type Meta struct {
	Total   int64 `json:"total"`
	PerPage int   `json:"per_page"`
}

type Paginated struct {
	Data interface{} `json:"data"`
	Meta Meta        `json:"meta"`
}

func GetPaginationFromRequest(c echo.Context) (int, int) {
	page, _ := strconv.Atoi(c.QueryParam("page"))
	if page == 0 {
		page = 1
	}

	pageSize, _ := strconv.Atoi(c.QueryParam("per_page"))

	switch {
	case pageSize > 100:
		pageSize = 100
	case pageSize <= 0:
		pageSize = 10
	}
	return page, pageSize
}

func Paginate(page int, pageSize int) func(db *gorm.DB) *gorm.DB {
	return func(db *gorm.DB) *gorm.DB {
		offset := (page - 1) * pageSize
		return db.Offset(offset).Limit(pageSize)
	}
}
