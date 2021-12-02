package album

import (
	"context"
	"net/http"
	"testing"

	"github.com/stretchr/testify/assert"
)

func TestDecodeGetAlbumListRequest(t *testing.T) {
	testCases := []struct {
		name     string
		url      string
		expected getAlbumListRequest
	}{
		{"Should default pagination", "http://localhost:3000/albums", getAlbumListRequest{PaginationParams: PaginationParams{Limit: 10, Next: 0}}},
		{"Should change pagination settings", "http://localhost:3000/albums?next=123&limit=100", getAlbumListRequest{PaginationParams: PaginationParams{Limit: 100, Next: 123}}},
		{"Should include join categories", "http://localhost:3000/albums?join=categories", getAlbumListRequest{PaginationParams: PaginationParams{Limit: 10, Next: 0}, Joins: AlbumListJoinsParams{Categories: true}}},
		{"Should include join medias", "http://localhost:3000/albums?join=medias", getAlbumListRequest{PaginationParams: PaginationParams{Limit: 10, Next: 0}, Joins: AlbumListJoinsParams{Medias: true}}},
		{"Should include join categories & medias", "http://localhost:3000/albums?join=medias,categories", getAlbumListRequest{PaginationParams: PaginationParams{Limit: 10, Next: 0}, Joins: AlbumListJoinsParams{Medias: true, Categories: true}}},
	}
	for _, tc := range testCases {
		t.Run(tc.name, func(t *testing.T) {
			request, err := http.NewRequest("GET", tc.url, nil)
			if err != nil {
				t.Error(err)
			}

			r, err := decodeGetAlbumListRequest(context.Background(), request)
			if err != nil {
				t.Error(err)
			}

			assert.Equal(t, tc.expected, r)
		})
	}
}
