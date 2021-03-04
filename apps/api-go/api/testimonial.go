package api

import (
	"net/http"
	"strconv"
	"time"

	"github.com/labstack/echo/v4"
)

// Testimonial
type Testimonial struct {
	ID        string    `json:"id"`
	Message   string    `json:"message"`
	Name      string    `json:"name"`
	CreatedAt time.Time `json:"createdAt"`
	UpdatedAt time.Time `json:"updatedAt"`
}

type Testimonials []Testimonial

var testimonials = []Testimonial{
	{
		ID:        "azeaze",
		Message:   "azeaze",
		Name:      "azeaze",
		CreatedAt: time.Now(),
		UpdatedAt: time.Now(),
	},
}

func GetTestimonials(c echo.Context) error {
	return c.JSON(http.StatusOK, testimonials)
}

func GetTestimonial(c echo.Context) error {
	id, _ := strconv.Atoi(c.Param("id"))
	return c.JSON(http.StatusOK, testimonials[id])
}
