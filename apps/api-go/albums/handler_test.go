package albums

import (
	"api-go/db"
	"net/http"
	"net/http/httptest"
	"testing"
	"time"

	"github.com/guregu/null"
	"github.com/kinbiko/jsonassert"
	echo "github.com/labstack/echo/v4"
	"github.com/stretchr/testify/assert"
)

func TestGetAlbums(t *testing.T) {
	// Setup
	e := echo.New()
	db, _ := db.Init()

	t.Run("should be able to list albums", func(t *testing.T) {
		err := db.Where("1 = 1").Delete(&Album{}).Error
		if err != nil {
			panic(err)
		}

		req := httptest.NewRequest(http.MethodGet, "/", nil)
		rec := httptest.NewRecorder()
		c := e.NewContext(req, rec)
		c.SetPath("/albums")

		// Assertions
		if assert.NoError(t, GetAlbums(c)) {
			assert.Equal(t, http.StatusOK, rec.Code)
			assert.Equal(t, `{"data":[],"meta":{"total":0,"per_page":10}}`+"\n", rec.Body.String())
		}
	})
}

func TestGetAlbum(t *testing.T) {
	// Setup
	e := echo.New()
	db, _ := db.Init()

	t.Run("should return 404 on with bad slug", func(t *testing.T) {
		req := httptest.NewRequest(http.MethodGet, "/", nil)
		rec := httptest.NewRecorder()
		c := e.NewContext(req, rec)
		c.SetPath("/albums/:slug")
		c.SetParamNames("slug")
		c.SetParamValues("not-found-album")

		// Assertions
		err := GetAlbum(c)
		if assert.Error(t, err) {
			he, ok := err.(*echo.HTTPError)
			if ok {
				assert.Equal(t, http.StatusNotFound, he.Code)
			}
		}
	})

	t.Run("should be able to get an album", func(t *testing.T) {

		err := db.Where("1 = 1").Delete(&Album{}).Error
		if err != nil {
			panic(err)
		}
		album := map[string]interface{}{
			"Title": "A good album", "Slug": "a-good-album", "Private": false, "PublishedAt": null.NewTime(time.Now(), true),
		}
		if err := db.Model(Album{}).Create(album).Error; err != nil {
			panic(err)
		}

		req := httptest.NewRequest(http.MethodGet, "/", nil)
		rec := httptest.NewRecorder()
		c := e.NewContext(req, rec)
		c.SetPath("/albums/:slug")
		c.SetParamNames("slug")
		c.SetParamValues("a-good-album")

		// Assertions
		ja := jsonassert.New(t)
		expJSON := `{"id":"<<PRESENCE>>","slug":"a-good-album","title":"A good album","body":null,"published_at":"<<PRESENCE>>","private":false,"user_id":null,"created_at":null,"updated_at":null,"notify_users_on_published":true,"meta_description":"","sso_id":null}` + "\n"
		if assert.NoError(t, GetAlbum(c)) {
			assert.Equal(t, http.StatusOK, rec.Code)
			ja.Assertf(rec.Body.String(), expJSON)
			// assert.Equal(t, expJSON, rec.Body.String())
		}
	})
}
