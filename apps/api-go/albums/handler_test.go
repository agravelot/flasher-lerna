package albums

import (
	"api-go/categories"
	"api-go/db"
	"encoding/json"
	"fmt"
	"log"
	"net/http"
	"net/http/httptest"
	"testing"
	"time"

	"github.com/guregu/null"
	"github.com/kinbiko/jsonassert"
	echo "github.com/labstack/echo/v4"
	"github.com/stretchr/testify/assert"
	"gorm.io/gorm"
)

func ClearDB(db *gorm.DB) {
	var tables []string
	if err := db.Table("information_schema.tables").Where("table_schema = ?", "public").Pluck("table_name", &tables).Error; err != nil {
		panic(err)
	}
	for _, table := range tables {
		db.Exec("DELETE FROM " + table + " WHERE 1 = 1")
	}

	// test category
	c := categories.Category{Name: "Jinzhu"}
	res := db.Create(&c)
	fmt.Println(res)
}

func TestListAlbums(t *testing.T) {
	// Setup
	e := echo.New()
	db, _ := db.Init()
	Setup(e)
	categories.Setup(e)

	t.Run("should be able to list albums", func(t *testing.T) {
		ClearDB(db)

		req := httptest.NewRequest(http.MethodGet, "/", nil)
		rec := httptest.NewRecorder()
		c := e.NewContext(req, rec)
		c.SetPath("/albums")

		// Assertions
		if assert.NoError(t, ListAlbums(c)) {
			assert.Equal(t, http.StatusOK, rec.Code)
			assert.Equal(t, `{"data":[],"meta":{"total":0,"per_page":10}}`+"\n", rec.Body.String())
		}
	})

	t.Run("should be able to list public albums", func(t *testing.T) {
		ClearDB(db)
		albums := []map[string]interface{}{
			{
				"Title": "A good album", "Slug": "a-good-album", "Private": false, "PublishedAt": null.NewTime(time.Now(), true),
			},
			{
				"Title": "A good album", "Slug": "a-good-album-non-published", "Private": false,
			},
			{
				"Title": "A good album", "Slug": "a-good-album-private", "Private": true, "PublishedAt": null.NewTime(time.Now(), true),
			},
		}
		for _, a := range albums {
			if err := db.Model(Album{}).Create(a).Error; err != nil {
				panic(err)
			}
		}

		req := httptest.NewRequest(http.MethodGet, "/", nil)
		rec := httptest.NewRecorder()
		c := e.NewContext(req, rec)
		c.SetPath("/albums")

		// Assertions
		if assert.NoError(t, ListAlbums(c)) {
			result := PaginatedAlbums{}

			assert.Equal(t, http.StatusOK, rec.Code)
			if err := json.NewDecoder(rec.Body).Decode(&result); err != nil {
				log.Fatalln(err)
			}
			assert.Equal(t, 10, result.Meta.PerPage)
			assert.Equal(t, 1, len(result.Data), "only one album should be avaiblable publicly")
			assert.Equal(t, result.Data[0].Slug, "a-good-album")
			// So(len(result), ShouldEqual, 0)
			// assert.Equal(t, `{"data":[],"meta":{"total":0,"per_page":10}}`+"\n", rec.Body.String())
		}
	})
}

func TestShowAlbum(t *testing.T) {
	// Setup
	e := echo.New()
	db, _ := db.Init()

	tt := []struct {
		name   string
		slug   string
		status int
		err    string
		body   string
		albums []map[string]interface{}
	}{
		{
			name:   "should return 404 on with bad slug",
			slug:   "not-found-album",
			status: http.StatusNotFound,
			err:    "Album not found.",
			albums: []map[string]interface{}{{
				"Title": "A good album", "Slug": "a-good-album", "Private": false, "PublishedAt": null.NewTime(time.Now(), true),
			}},
		},
		{
			name:   "should be able to get an album",
			slug:   "a-good-album",
			status: http.StatusOK,
			err:    "",
			albums: []map[string]interface{}{{
				"Title": "A good album", "Slug": "a-good-album", "Private": false, "PublishedAt": null.NewTime(time.Now(), true),
			}},
			body: `{"id":"<<PRESENCE>>","slug":"a-good-album","title":"A good album","body":null,"published_at":"<<PRESENCE>>","private":false,"user_id":null,"created_at":null,"updated_at":null,"notify_users_on_published":true,"meta_description":"","sso_id":null}` + "\n",
		},
	}

	for _, tc := range tt {
		t.Run(tc.name, func(t *testing.T) {

			// Arrange
			ClearDB(db)
			for _, a := range tc.albums {
				if err := db.Model(Album{}).Create(a).Error; err != nil {
					panic(err)
				}
			}

			// Act
			req := httptest.NewRequest(http.MethodGet, "/", nil)
			rec := httptest.NewRecorder()
			c := e.NewContext(req, rec)
			c.SetPath("/albums/:slug")
			c.SetParamNames("slug")
			c.SetParamValues(tc.slug)

			// Assert
			if tc.err != "" {
				if err := ShowAlbum(c); assert.Error(t, err) {
					he, ok := err.(*echo.HTTPError)
					if ok {
						assert.Equal(t, tc.status, he.Code)
					}
				}
			} else {
				ja := jsonassert.New(t)
				if assert.NoError(t, ShowAlbum(c)) {
					assert.Equal(t, tc.status, rec.Code)
					ja.Assertf(rec.Body.String(), tc.body)
				}
			}
		})
	}
}
