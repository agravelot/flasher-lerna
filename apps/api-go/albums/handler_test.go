package albums

import (
	"api-go/config"
	database "api-go/db"
	"encoding/json"
	"log"
	"net/http"
	"net/http/httptest"
	"testing"
	"time"

	"github.com/guregu/null"
	echo "github.com/labstack/echo/v4"
	"github.com/stretchr/testify/assert"
)

func TestListAlbums(t *testing.T) {
	// Setup
	e := echo.New()
	config := config.LoadDotEnv("../")
	db, _ := database.Init(config)
	Setup(e)
	// Setup(e)

	t.Run("should be able to list albums", func(t *testing.T) {
		database.ClearDB(db)

		req := httptest.NewRequest(http.MethodGet, "/", nil)
		rec := httptest.NewRecorder()
		c := e.NewContext(req, rec)
		c.SetPath("/albums")

		// Assertions
		if assert.NoError(t, ListAlbums(c)) {
			assert.Equal(t, http.StatusOK, rec.Code)
			assert.Equal(t, `{"data":[],"meta":{"total":0,"limit":10}}`+"\n", rec.Body.String())
		}
	})

	t.Run("should be able to list public albums", func(t *testing.T) {
		database.ClearDB(db)
		albums := []map[string]interface{}{
			{
				"Title": "A good album", "Slug": "a-good-album", "Private": false, "PublishedAt": null.NewTime(time.Now(), true), "MetaDescription": "qzdqsd",
			},
			{
				"Title": "A good album non-published", "Slug": "a-good-album-non-published", "Private": false, "MetaDescription": "qzdqsd",
			},
			{
				"Title": "A good album private", "Slug": "a-good-album-private", "Private": true, "PublishedAt": null.NewTime(time.Now(), true), "MetaDescription": "qzdqsd",
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
			assert.Equal(t, 10, result.Meta.Limit)
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
	config := config.LoadDotEnv("../")
	db, _ := database.Init(config)

	now := null.NewTime(time.Now(), true)

	tt := []struct {
		name     string
		slug     string
		status   int
		err      string
		albums   []map[string]interface{}
		expected Album
	}{
		{
			name:   "should return 404 on with bad slug",
			slug:   "not-found-album",
			status: http.StatusNotFound,
			err:    "Album not found.",
			albums: []map[string]interface{}{{
				"Title": "A good album", "Slug": "a-good-album", "Private": false, "PublishedAt": now, "MetaDescription": "qzdqsd",
			}},
		},
		{
			name:   "should be able to get an album",
			slug:   "a-good-album",
			status: http.StatusOK,
			err:    "",
			albums: []map[string]interface{}{{
				"Title": "A good album", "Slug": "a-good-album", "Private": false, "PublishedAt": now, "MetaDescription": "qzdqsd",
			}},
			expected: Album{Title: "A good album", Private: false, PublishedAt: now, MetaDescription: "qzdqsd", NotifyUsersOnPublished: true},
		},
	}

	for _, tc := range tt {
		t.Run(tc.name, func(t *testing.T) {

			// Arrange
			database.ClearDB(db)
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
				if assert.NoError(t, ShowAlbum(c)) {
					assert.Equal(t, tc.status, rec.Code)
					result := Album{}
					if err := json.NewDecoder(rec.Body).Decode(&result); err != nil {
						log.Fatalln(err)
					}

					assert.Equal(t, tc.expected.Title, result.Title)
					assert.Equal(t, tc.expected.MetaDescription, result.MetaDescription)
					assert.Equal(t, tc.expected.Private, result.Private)
					assert.Equal(t, tc.expected.Body, result.Body)
					assert.Equal(t, tc.expected.NotifyUsersOnPublished, result.NotifyUsersOnPublished, "should notify user by defualt")
					assert.Equal(t, tc.expected.SsoID, result.SsoID)
				}
			}
		})
	}
}
