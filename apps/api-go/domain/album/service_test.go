package album_test

import (
	"context"
	"errors"
	"fmt"
	"log"
	"os"
	"strconv"
	"testing"
	"time"

	"api-go/config"
	"api-go/domain/album"
	albums_pb "api-go/gen/go/proto/albums/v2"
	"api-go/infrastructure/storage/postgres"
	"api-go/model"
	"api-go/pkg/auth"
	"api-go/query"

	"github.com/jackc/pgconn"
	"github.com/jackc/pgerrcode"
	"github.com/kr/pretty"
	"github.com/stretchr/testify/assert"
	"google.golang.org/protobuf/types/known/timestamppb"
)

var db postgres.Postgres

var ssoId = "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"

// var token = "eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICIxamVHNzFZSHlUd25GUEVSb2NJeEVzS21lbjlWN1NjanRIZXFzak1KUXlZIn0.eyJleHAiOjE2MTExNjQ3MTAsImlhdCI6MTYxMTE2NDQxMCwiYXV0aF90aW1lIjoxNjExMTY0MzY0LCJqdGkiOiJlMThlMWNlOC05OTc5LTQ3NmQtOWYxMC1mOTk5OWJhMDQwZjgiLCJpc3MiOiJodHRwczovL2FjY291bnRzLmFncmF2ZWxvdC5ldS9hdXRoL3JlYWxtcy9hbnljbG91ZCIsImF1ZCI6ImFjY291bnQiLCJzdWIiOiIzMDE1MWFlNS0yOGI0LTRjNmMtYjBhZS1lYTJlNmE0OWVmNjciLCJ0eXAiOiJCZWFyZXIiLCJhenAiOiJmcm9udGVuZCIsIm5vbmNlIjoiMTkyOWEwZGEtMTU2ZS00NWZmLTgzM2YtYTU2MGIwNmI1YWNkIiwic2Vzc2lvbl9zdGF0ZSI6IjRlMWYxOWYzLTFhMmMtNGUxNS1iMWFhLTNlY2ZhMTkxMGRiOCIsImFjciI6IjAiLCJhbGxvd2VkLW9yaWdpbnMiOlsiaHR0cDovL2xvY2FsaG9zdDo4MDgwIiwiaHR0cDovL2xvY2FsaG9zdDo4MDgxIl0sInJlYWxtX2FjY2VzcyI6eyJyb2xlcyI6WyJvZmZsaW5lX2FjY2VzcyIsInVtYV9hdXRob3JpemF0aW9uIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnsiYWNjb3VudCI6eyJyb2xlcyI6WyJtYW5hZ2UtYWNjb3VudCIsIm1hbmFnZS1hY2NvdW50LWxpbmtzIiwidmlldy1wcm9maWxlIl19fSwic2NvcGUiOiJvcGVuaWQgcHJvZmlsZSBlbWFpbCIsImVtYWlsX3ZlcmlmaWVkIjp0cnVlLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiJ0ZXN0IiwiZW1haWwiOiJ0ZXN0QHRlc3QuY29tIn0.PkfxSmIiG4lLE9hCjICcRPNpXC0X2QtVzYeUwAUwwe2G_6ArmMdZOkRVOKx3jiRO7PYu-D0NR9tAiv7yN9SDMDrIhtNoosgChB4PQ4wBf_YvHsJaAHwyK8Hu6h_8gxJIl3UYCKWTSYgLRK-IOE9E6FNlMdJK9UXAO_y2IBEZBO9QV-QxZH7SlYkm8VfoZzNzRMy82SgWLsQGDvwAAGCxHFRgTZdFNKPoqJylDyANBEuWanLwDohQKdNGqz6PlhtopmXo1v8kcHwBHxyMQ3mtRNCXBV6TOXo7oAWW3XeXGWjTtAiTY85Wr7R6IJ74WKpMrG-3PDL6Sx6n4JxOuurpLg"

var userSsoId = "30151ae5-28b4-4c6c-b0ae-ea2e6a49ef67"

func authAsUser(ctx context.Context) (context.Context, auth.Claims) {
	claims := auth.Claims{
		Exp:            1611164710,
		Iat:            1611164410,
		AuthTime:       1611164364,
		Jti:            "e18e1ce8-9979-476d-9f10-f9999ba040f8",
		Iss:            "https://accounts.example.com/auth/realms/test",
		Aud:            "account",
		Sub:            userSsoId,
		Typ:            "Bearer",
		Azp:            "frontend",
		Nonce:          "1929a0da-156e-45ff-833f-a560b06b5acd",
		SessionState:   "4e1f19f3-1a2c-4e15-b1aa-3ecfa1910db8",
		Acr:            "0",
		AllowedOrigins: []string{},
		RealmAccess: auth.RealmAccess{
			Roles: []string{},
		},
		ResourceAccess: auth.ResourceAccess{
			Account: auth.Account{
				Roles: []string{},
			},
		},
		Scope:             "openid profile email",
		EmailVerified:     true,
		PreferredUsername: "test",
		Email:             "test@test.com",
	}

	return context.WithValue(ctx, auth.UserClaimsKey, &claims), claims
}

var adminSsoID = "30151ae5-28b4-4c6c-b0ae-ea2e6a49ef99"

func authAsAdmin(ctx context.Context) (context.Context, auth.Claims) {
	claims := auth.Claims{
		Exp:            1611164710,
		Iat:            1611164410,
		AuthTime:       1611164364,
		Jti:            "e18e1ce8-9979-476d-9f10-f9999ba040f8",
		Iss:            "https://accounts.example.com/auth/realms/test",
		Aud:            "account",
		Sub:            adminSsoID,
		Typ:            "Bearer",
		Azp:            "frontend",
		Nonce:          "1929a0da-156e-45ff-833f-a560b06b5acd",
		SessionState:   "4e1f19f3-1a2c-4e15-b1aa-3ecfa1910db8",
		Acr:            "0",
		AllowedOrigins: []string{},
		RealmAccess: auth.RealmAccess{
			Roles: []string{"admin"},
		},
		ResourceAccess: auth.ResourceAccess{
			Account: auth.Account{
				Roles: []string{},
			},
		},
		Scope:             "openid profile email",
		EmailVerified:     true,
		PreferredUsername: "test",
		Email:             "test@test.com",
	}

	return context.WithValue(ctx, auth.UserClaimsKey, &claims), claims
}

func TestMain(m *testing.M) {
	config, err := config.FromDotEnv("../../.env")
	if err != nil {
		log.Fatal(fmt.Errorf("unable to load config: %w", err))
	}
	_db, err := postgres.New(config.Database.URL)
	if err != nil {
		log.Fatal(fmt.Errorf("unable to connect to the database: %w", err))
	}
	db = _db

	exitVal := m.Run() // Run tests
	// Do stuff after test

	os.Exit(exitVal)
}

// func setupTest() func() {
// 	// Setup here

// 	// Teardown here
// 	return func() {}
// }

/////// LIST ////////

type ListTestCase struct {
	name                     string
	expectedDataCount        int
	albumGenerator           func() []*model.Album
	assertSlugOrder          []string
	assertTitleOrder         []string
	assertCategoriesPresence bool
	assertCategoriesCount    []int
	assertMediaPresence      bool
	assertMediaCount         []int
	limit                    *int32
	joins                    struct {
		Categories bool
		Medias     bool
	}
	nextGenerator func(albums []*model.Album) int32
	authAs        func(ctx context.Context) (context.Context, auth.Claims)
}

var (
	sub10Min  = time.Now().Add(-10 * time.Minute)
	sub100Min = time.Now().Add(-100 * time.Minute)
	sub5Min   = time.Now().Add(-5 * time.Minute)
)

func ptr[T any](i T) *T {
	return &i
}

func TestList(t *testing.T) {
	testListCases := []ListTestCase{
		{name: "should be able to list empty"},
		{
			name:              "list with one published album",
			expectedDataCount: 1,
			albumGenerator: func() []*model.Album {
				return []*model.Album{
					{
						Title:       "A good Title",
						PublishedAt: &sub10Min,
						Private:     ptr(false),
						SsoID:       &ssoId,
					},
				}
			},
		},
		{
			name:              "should be ordered by date of publication",
			expectedDataCount: 3,
			assertSlugOrder:   []string{"a-good-title-3", "a-good-title-2", "a-good-title"},
			albumGenerator: func() []*model.Album {
				return []*model.Album{
					{
						Title:       "A good Title",
						Slug:        "a-good-title",
						PublishedAt: &sub100Min,
						Private:     ptr(false),
						SsoID:       &ssoId,
					},
					{
						Title:       "A good Title 2",
						Slug:        "a-good-title-2",
						PublishedAt: &sub10Min,
						Private:     ptr(false),
						SsoID:       &ssoId,
					},
					{
						Title:       "A good Title 3",
						Slug:        "a-good-title-3",
						PublishedAt: &sub5Min,
						Private:     ptr(false),
						SsoID:       &ssoId,
					},
				}
			},
		},
		{
			name:              "only show public albums",
			expectedDataCount: 1,
			albumGenerator: func() []*model.Album {
				return []*model.Album{
					{
						Title:       "A good Title",
						Slug:        "a-good-title",
						PublishedAt: &sub100Min,
						Private:     ptr(false),
						SsoID:       &ssoId,
					},
					{
						Title:       "A good Title 2",
						Slug:        "a-good-title-2",
						PublishedAt: &sub10Min,
						Private:     ptr(true),
						SsoID:       &ssoId,
					},
					{
						Title:   "A good Title 3",
						Slug:    "a-good-title-3",
						Private: ptr(true),
						SsoID:   &ssoId,
					},
					{
						Title:   "A good Title 4",
						Slug:    "a-good-title-4",
						Private: ptr(false),
						SsoID:   &ssoId,
					},
				}
			},
		},
		{
			name:              "should be able to list published albums on second page",
			expectedDataCount: 1,
			nextGenerator: func(albums []*model.Album) int32 {
				a := *albums[9]
				return a.ID
			},
			albumGenerator: func() []*model.Album {
				var albums []*model.Album
				for i := 0; i < 10; i++ {
					albums = append(albums, &model.Album{
						Title:       "A good Title " + strconv.Itoa(i),
						Slug:        "a-good-title-" + strconv.Itoa(i),
						PublishedAt: &sub100Min,
						Private:     ptr(false),
						SsoID:       &ssoId,
					})
				}

				albums = append(albums, &model.Album{
					Title:       "On second page",
					Slug:        "on-second-page",
					PublishedAt: &sub5Min,
					Private:     ptr(false),
					SsoID:       &ssoId,
				})
				return albums
			},
		},
		{
			name: "should be able to list published albums on second page with custom per page",
			nextGenerator: func(albums []*model.Album) int32 {
				return albums[1].ID
			},
			limit:             ptr[int32](2),
			expectedDataCount: 1,
			assertTitleOrder:  []string{"On second page"},
			assertSlugOrder:   []string{"on-second-page"},
			albumGenerator: func() []*model.Album {
				var albums []*model.Album
				for i := 0; i < 2; i++ {
					albums = append(albums, &model.Album{
						Title:       "On second page " + strconv.Itoa(i),
						Slug:        "on-second-page " + strconv.Itoa(i),
						PublishedAt: &sub5Min,
						Private:     ptr(false),
						SsoID:       &ssoId,
					})
				}

				albums = append(albums, &model.Album{
					Title:       "On second page",
					Slug:        "on-second-page",
					PublishedAt: &sub5Min,
					Private:     ptr(false),
					SsoID:       &ssoId,
				})
				return albums
			},
		},
		{
			name:              "should be able to list non published album as admin",
			expectedDataCount: 1,
			authAs:            authAsAdmin,
			albumGenerator: func() []*model.Album {
				return []*model.Album{
					{
						Title: "On second page",
						Slug:  "on-second-page",
						SsoID: &ssoId,
					},
				}
			},
		},
		{
			name:                     "Should not include categories by default",
			assertCategoriesPresence: false,
			expectedDataCount:        1,
			albumGenerator: func() []*model.Album {
				c := model.Category{
					Name: "A good Category",
				}
				return []*model.Album{
					{
						Title:       "A good Title",
						PublishedAt: &sub5Min,
						SsoID:       &ssoId,
						Private:     ptr(false),
						Categories:  []model.Category{c},
					},
				}
			},
		},
		{
			name:                     "Should be able to list with categories",
			assertCategoriesPresence: true,
			assertCategoriesCount:    []int{1},
			joins: struct {
				Categories bool
				Medias     bool
			}{Categories: true},
			expectedDataCount: 1,
			albumGenerator: func() []*model.Album {
				c := model.Category{
					Name: "A good Category",
				}
				return []*model.Album{
					{
						Title:       "A good Title",
						PublishedAt: &sub5Min,
						SsoID:       &ssoId,
						Private:     ptr(false),
						Categories:  []model.Category{c},
					},
				}
			},
		},
		{
			name:                "Should be able to list with medias",
			assertMediaPresence: true,
			assertMediaCount:    []int{1},
			joins: struct {
				Categories bool
				Medias     bool
			}{Medias: true},
			expectedDataCount: 1,
			albumGenerator: func() []*model.Album {
				mimeType := "image/jpeg"
				arg1 := model.Medium{
					Name: "A good Media",
					// ModelID:          int64(arg.ID),
					Size:             int64(1),
					ModelType:        "App\\Models\\Album",
					CollectionName:   "albums",
					Disk:             "dummy",
					MimeType:         &mimeType,
					Manipulations:    `{"resize":{"width":100,"height":100}}`,
					CustomProperties: &model.CustomProperties{},
					ResponsiveImages: &model.ResponsiveImages{},
				}
				return []*model.Album{
					{
						Title:       "A good Title",
						PublishedAt: &sub5Min,
						SsoID:       &ssoId,
						Private:     ptr(false),
						Medias:      []model.Medium{arg1},
					},
				}
			},
		},
		{
			name:                "Should not include medias by default",
			assertMediaPresence: false,
			expectedDataCount:   1,
			albumGenerator: func() []*model.Album {
				mimeType := "image/jpeg"
				arg1 := model.Medium{
					Name: "A good Media",
					// ModelID:          int64(arg.ID),
					Size:             int64(1),
					ModelType:        "App\\Models\\Album",
					CollectionName:   "albums",
					Disk:             "dummy",
					MimeType:         &mimeType,
					Manipulations:    `{"resize":{"width":100,"height":100}}`,
					CustomProperties: &model.CustomProperties{},
					ResponsiveImages: &model.ResponsiveImages{},
				}
				return []*model.Album{
					{
						Title:       "A good Title",
						PublishedAt: &sub5Min,
						SsoID:       &ssoId,
						Private:     ptr(false),
						Medias:      []model.Medium{arg1},
					},
				}
			},
		},
		{
			name:              "Should not list non published albums as guest",
			expectedDataCount: 0,
			albumGenerator: func() []*model.Album {
				return []*model.Album{
					{
						Title:   "A good Title",
						SsoID:   &ssoId,
						Private: ptr(false),
					},
				}
			},
		},
		{
			name:              "should not list non published albums as user",
			expectedDataCount: 0,
			authAs:            authAsUser,
			albumGenerator: func() []*model.Album {
				return []*model.Album{
					{
						Title:   "A good Title",
						SsoID:   &adminSsoID,
						Private: ptr(false),
					},
				}
			},
		},
		{
			name:              "should not be able to list his non published private albums as user",
			expectedDataCount: 0,
			authAs:            authAsUser,
			albumGenerator: func() []*model.Album {
				return []*model.Album{
					{
						Title:       "A good Title",
						SsoID:       &userSsoId,
						PublishedAt: &sub5Min,
						Private:     ptr(true),
					},
				}
			},
		},
		{
			// TODO
			name: "Should be able to see his published private albums appearing as cosplayer as user",
		},
	}

	for _, tc := range testListCases {
		tc := tc // capture range variable
		t.Run(tc.name, func(t *testing.T) {
			t.Parallel()

			tx := db.Begin()
			defer tx.Rollback()
			repo, err := postgres.NewAlbumRepository(&tx)
			if err != nil {
				t.Error(err)
			}
			s, err := album.NewService(repo)
			if err != nil {
				t.Error(err)
			}

			ctx := context.Background()
			if tc.authAs != nil {
				ctx, _ = tc.authAs(ctx)
			}

			var albums []*model.Album
			if tc.albumGenerator != nil {
				albums = tc.albumGenerator()
			}
			q := query.Use(tx.DB).Album
			err = q.WithContext(ctx).Create(albums...)
			if err != nil {
				t.Error(err)
			}

			params := &albums_pb.IndexRequest{
				Limit: tc.limit,
				Joins: &albums_pb.IndexRequest_Joins{
					Categories: tc.joins.Categories,
					Medias:     tc.joins.Medias,
				},
			}

			if tc.nextGenerator != nil {
				next := tc.nextGenerator(albums)
				params.Next = &next
			}

			r, err := s.Index(ctx, params)

			assert.NoError(t, err)
			assert.Equal(t, tc.expectedDataCount, len(r.Data))

			for i := range r.Data {
				assert.Equal(t, tc.assertCategoriesPresence, r.Data[i].Categories != nil)
				if tc.assertCategoriesPresence {
					assert.Equal(t, tc.assertCategoriesCount[i], len(r.Data[i].Categories))
				}

				assert.Equal(t, tc.assertMediaPresence, r.Data[i].Medias != nil)
				if tc.assertMediaPresence {
					assert.Equal(t, tc.assertMediaCount[i], len(r.Data[i].Medias))
				}
			}

			for i, s := range tc.assertTitleOrder {
				assert.Equal(t, s, r.Data[i].Title)
			}

			for i, s := range tc.assertSlugOrder {
				assert.Equal(t, s, r.Data[i].Slug)
			}
		})
	}
}

///////// SHOW  ///////////

func TestShouldBeAbleToGetPublishedAlbumAsGuest(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     ptr(false),
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetBySlug(context.Background(), &albums_pb.GetBySlugRequest{Slug: arg.Slug})

	assert.NoError(t, err)
	assert.Equal(t, arg.Slug, r.Album.Slug)
}

func TestShouldBeAbleToGetPublishedAlbumAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsUser(context.Background())
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	sub5Min := time.Now().Add(-5 * time.Minute)
	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title:       "A good Title",
		PublishedAt: &sub5Min,
		SsoID:       &ssoId,
		Private:     ptr(false),
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetBySlug(ctx, &albums_pb.GetBySlugRequest{Slug: arg.Slug})

	assert.NoError(t, err)
	assert.Equal(t, arg.Slug, r.Album.Slug)
}

func TestShouldNotBeAbleToGetNonPublishedAlbumAsGuest(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	arg := model.Album{
		Title: "A good Title",
		SsoID: &ssoId,
		Slug:  slug,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	_, err = s.GetBySlug(context.Background(), &albums_pb.GetBySlugRequest{Slug: slug})

	assert.Error(t, err)
	assert.Equal(t, album.ErrNotFound, err)
}

func TestShouldNotBeAbleToGetNonPublishedAlbumAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsUser(context.Background())
	ssoId := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title: "A good Title",
		SsoID: &ssoId,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	_, err = s.GetBySlug(ctx, &albums_pb.GetBySlugRequest{Slug: arg.Slug})

	assert.Error(t, err)
	assert.Equal(t, album.ErrNotFound, err)
}

func TestShouldBeAbleToGetNonPublishedAlbumAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	arg := model.Album{
		Title: "A good Title",
		SsoID: &ssoId,
	}

	err = a.WithContext(context.Background()).Create(&arg)
	if err != nil {
		t.Error(fmt.Errorf("Error creating album: %w", err))
	}

	r, err := s.GetBySlug(ctx, &albums_pb.GetBySlugRequest{Slug: arg.Slug})

	assert.NoError(t, err)
	assert.Equal(t, arg.Slug, r.Album.Slug)
}

func TestShouldHaveNotFoundFornonExistingAlbum(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())

	r, err := s.GetBySlug(ctx, &albums_pb.GetBySlugRequest{Slug: "not-existing"})

	pretty.Log(r)
	assert.ErrorAs(t, err, &album.ErrNotFound)
}

// ///////// POST  ///////////

func TestShouldBeAbleToCreateAnAlbumAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, claims := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	arg := &albums_pb.CreateRequest{
		Name:            "A good Title",
		MetaDescription: "meta",
		Slug:            "a-good-title",
	}
	res, err := s.Create(ctx, arg)

	assert.NoError(t, err)
	total, err := a.WithContext(context.Background()).Count()
	if err != nil {
		t.Error(fmt.Errorf("Error counting albums: %w", err))
	}
	assert.Equal(t, 1, int(total))
	assert.Equal(t, arg.Name, res.Title)
	assert.Equal(t, "a-good-title", res.Slug)
	assert.Equal(t, claims.Sub, res.AuthorId)
	assert.Equal(t, arg.PublishedAt, res.PublishedAt)
}

func TestShouldBeAbleToCreateAnPublishedAlbumAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, claims := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute).UTC()
	arg := albums_pb.CreateRequest{
		Name:            "A good Title",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	res, err := s.Create(ctx, &arg)

	assert.NoError(t, err)
	total, err := a.WithContext(context.Background()).Count()
	if err != nil {
		t.Error(fmt.Errorf("Error counting albums: %w", err))
	}
	assert.Equal(t, 1, int(total))
	assert.Equal(t, arg.Name, res.Title)
	assert.Equal(t, "a-good-title", res.Slug)
	assert.Equal(t, claims.Sub, res.AuthorId)
	assert.Equal(t, arg.PublishedAt.Seconds, res.PublishedAt.Seconds)
}

func TestShouldNotBeAbleToCreateAnAlbumWithSameSlug(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "A good Title",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)
	assert.NoError(t, err)

	tx.DB.SavePoint("beforeCreateDuplicate")
	_, err = s.Create(ctx, &arg)
	tx.DB.RollbackTo("beforeCreateDuplicate")

	var pgErr *pgconn.PgError
	assert.Error(t, err)
	assert.True(t, errors.As(err, &pgErr))
	assert.Equal(t, pgerrcode.UniqueViolation, pgErr.Code)

	total, err := a.WithContext(context.Background()).Count()
	assert.NoError(t, err)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithEmptyTitle(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)

	assert.Error(t, err)
	firstValidationError := err.(albums_pb.CreateRequestMultiError)[0].(albums_pb.CreateRequestValidationError)
	assert.Equal(t, "Name", firstValidationError.Field())
	total, err := a.WithContext(context.Background()).Count()
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithTooLongTitle(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "a very too long big endb.DBous title that will never fit in any screen...",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)

	assert.Error(t, err)
	firstValidationError := err.(albums_pb.CreateRequestMultiError)[0].(albums_pb.CreateRequestValidationError)
	assert.Equal(t, "Name", firstValidationError.Field())
	total, err := a.WithContext(context.Background()).Count()
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithEmptyMetaDescription(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "a good title",
		MetaDescription: "",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)

	assert.Error(t, err)
	firstValidationError := err.(albums_pb.CreateRequestMultiError)[0].(albums_pb.CreateRequestValidationError)
	assert.Equal(t, "MetaDescription", firstValidationError.Field())
	total, err := a.WithContext(context.Background()).Count()
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToSaveAlbumWithTooLongMetaDescription(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "a good title",
		MetaDescription: "a very too long big endb.DBous meta description that will never fit in any screen..a very too long big endb.DBous meta description that will never fit in any screen..a very too long big endb.DBous meta description that will never fit in any screen..a very too long big endb.DBous meta description that will never fit in any screen..a very too long big endb.DBous meta description that will never fit in any screen...",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)

	assert.Error(t, err)
	firstValidationError := err.(albums_pb.CreateRequestMultiError)[0].(albums_pb.CreateRequestValidationError)
	assert.Equal(t, "MetaDescription", firstValidationError.Field())
	total, err := a.WithContext(context.Background()).Count()
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToCreateAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsUser(context.Background())
	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "a good title",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(ctx, &arg)

	assert.Error(t, err)
	assert.Equal(t, album.ErrNotAdmin, err)
	total, err := a.WithContext(context.Background()).Count()

	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

func TestShouldNotBeAbleToCreateAsGuest(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	a := query.Use(tx.DB).Album

	slug := "a-good-title"
	pub := time.Now().Add(-5 * time.Minute)
	arg := albums_pb.CreateRequest{
		Name:            "a good title",
		MetaDescription: "meta",
		Slug:            slug,
		PublishedAt:     &timestamppb.Timestamp{Seconds: int64(pub.Second())},
	}

	_, err = s.Create(context.Background(), &arg)

	assert.Error(t, err)
	assert.Equal(t, album.ErrNoAuth, err)
	total, err := a.WithContext(context.Background()).Count() // TODO Add filter published to pass test
	assert.NoError(t, err)
	assert.Equal(t, 0, int(total))
}

// //////// UPDATE //////////

func TestShouldBeAbleToUpdateAlbumTitleAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	err = tx.DB.Create(&a).Error
	assert.NoError(t, err)

	expectedTitle := "A new Title"
	new, err := s.Update(ctx, &albums_pb.UpdateRequest{
		Id:              a.ID,
		Name:            expectedTitle,
		Slug:            a.Slug,
		MetaDescription: a.MetaDescription,
	})

	assert.NoError(t, err)
	assert.Equal(t, expectedTitle, new.Title)
	var total int64
	tx.DB.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateAlbumTooShortTitleAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	tx.DB.Create(&a)

	_, err = s.Update(ctx, &albums_pb.UpdateRequest{
		Id:              a.ID,
		Slug:            a.Slug,
		Name:            "",
		MetaDescription: a.MetaDescription,
	})

	assert.Error(t, err)
	assert.IsType(t, albums_pb.UpdateRequestMultiError{}, err)
	firstValidationError := err.(albums_pb.UpdateRequestMultiError)[0].(albums_pb.UpdateRequestValidationError)
	assert.Equal(t, "Name", firstValidationError.Field())
	var total int64
	tx.DB.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldBeAbleToUpdateAlbumToAsPublishedAsAdmin(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())

	ssoID := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &ssoID,
		Private:         ptr(true),
	}
	tx.DB.Create(&a)

	expectedTitle := "A new Title"
	new, err := s.Update(ctx, &albums_pb.UpdateRequest{
		Id:              a.ID,
		Name:            expectedTitle,
		Slug:            a.Slug,
		MetaDescription: a.MetaDescription,
		Private:         false,
		PublishedAt:     nil,
	})

	assert.NoError(t, err)
	assert.Equal(t, expectedTitle, new.Title)
	var expectedAlbum model.Album
	tx.DB.Model(&model.Album{}).Find(&expectedAlbum, a.ID)
	assert.Equal(t, a.ID, expectedAlbum.ID)
	assert.Equal(t, expectedTitle, expectedAlbum.Title)
	assert.Equal(t, false, new.Private)
	assert.Equal(t, false, *expectedAlbum.Private)
	assert.Nil(t, expectedAlbum.PublishedAt)
}

func TestShouldFailToUpdateNonExistingAlbumAndReturnNotFound(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())

	// expectedTitle := "A new Title"
	_, err = s.Update(ctx, &albums_pb.UpdateRequest{
		Id:              123,
		Name:            "qweqwe",
		Slug:            "a.Slug",
		MetaDescription: "a.MetaDescription",
		Private:         false,
		PublishedAt:     nil,
	})

	assert.Error(t, err)
	assert.ErrorAs(t, err, &album.ErrNotFound)
}

func TestShouldNotBeAbleToUpdateAlbumAsUser(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsUser(context.Background())

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	tx.DB.Create(&a)

	_, err = s.Update(ctx, &albums_pb.UpdateRequest{
		Id:   a.ID,
		Name: "A new Title",
	})

	assert.Error(t, err)
	assert.Equal(t, album.ErrNotAdmin, err)
	var total int64
	tx.DB.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

func TestShouldNotBeAbleToUpdateAlbumAsGuest(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	tx.DB.Create(&a)

	_, err = s.Update(context.Background(), &albums_pb.UpdateRequest{
		Id:   a.ID,
		Name: "A new Title",
	})

	assert.Error(t, err)
	assert.Equal(t, album.ErrNoAuth, err)
	var total int64
	tx.DB.Model(&model.Album{}).Count(&total)
	assert.Equal(t, 1, int(total))
}

// //////// DELETE //////////

func TestAdminShouldBeAbleToDeleteAndNotSoftDeleted(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())
	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            "a-good-slug",
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	_, err = s.Delete(ctx, &albums_pb.DeleteRequest{Id: a.ID})
	assert.NoError(t, err)

	var total, totalScopeless int64
	tx.DB.Model(&a).Count(&total)
	tx.DB.Model(&a).Unscoped().Count(&totalScopeless)
	assert.Equal(t, 0, int(total))
	assert.Equal(t, 0, int(totalScopeless))
}

func TestAdminShouldNotBeAbleToDeleteAnNonExistantAlbum(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsAdmin(context.Background())

	_, err = s.Delete(ctx, &albums_pb.DeleteRequest{Id: 1})

	assert.Error(t, err)
	assert.ErrorAs(t, err, &album.ErrNotFound)
}

func TestUserShouldNotBeAbleToDelete(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	ctx, _ := authAsUser(context.Background())
	slug := "a-good-slug"
	id := "a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a11"
	a := model.Album{
		Title:           "A good Title",
		Slug:            slug,
		MetaDescription: "a meta decription",
		SsoID:           &id,
	}
	err = tx.DB.Create(&a).Error
	if err != nil {
		t.Error(err)
	}

	_, err = s.Delete(ctx, &albums_pb.DeleteRequest{Id: a.ID})

	assert.Error(t, err)
	assert.EqualError(t, err, album.ErrNotAdmin.Error())
}

func TestGuestShouldNotBeAbleToDelete(t *testing.T) {
	tx := db.Begin()
	defer tx.Rollback()
	repo, err := postgres.NewAlbumRepository(&tx)
	if err != nil {
		t.Error(err)
	}
	s, err := album.NewService(repo)
	if err != nil {
		t.Error(err)
	}

	_, err = s.Delete(context.Background(), &albums_pb.DeleteRequest{Id: 1})

	assert.Error(t, err)
	assert.EqualError(t, err, album.ErrNoAuth.Error())
}
