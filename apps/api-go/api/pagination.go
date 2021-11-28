package api

import (
	"strconv"

	"github.com/labstack/echo/v4"
	"gorm.io/gorm"
)

type Meta struct {
	Total int64 `json:"total"`
	Limit int   `json:"limit"`
}

type Paginated struct {
	Data interface{} `json:"data"`
	Meta Meta        `json:"meta"`
}

func Paginatee(c echo.Context) func(db *gorm.DB) *gorm.DB {
	return func(db *gorm.DB) *gorm.DB {
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

		offset := (page - 1) * pageSize
		return db.Offset(offset).Limit(pageSize)
	}
}

func GetPaginationFromRequest(c echo.Context) (string, int) {
	page := c.QueryParam("page")

	pageSize, _ := strconv.Atoi(c.QueryParam("per_page"))

	switch {
	case pageSize > 100:
		pageSize = 100
	case pageSize <= 0:
		pageSize = 10
	}
	return page, pageSize
}

func Paginate(next uint, pageSize int) func(db *gorm.DB) *gorm.DB {
	return func(db *gorm.DB) *gorm.DB {
		if next != 0 {
			db = db.Where("id > ?", next)
		}
		return db.Limit(pageSize)
	}
}
