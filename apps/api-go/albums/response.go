package album

import (
	albumspb "api-go/gen/go/proto/albums/v1"
	"api-go/model"
	"time"

	"github.com/go-playground/validator/v10"
	"github.com/google/uuid"
	"google.golang.org/protobuf/types/known/timestamppb"
)

// use a single instance of Validate, it caches struct info
var validate *validator.Validate

type AlbumUpdateRequest struct {
	ID                     int64      `json:"id" example:"1"`
	Slug                   *string    `json:"slug" example:"a-good-album"`
	Title                  string     `json:"title" example:"A good album" validate:"lt=60"`
	Body                   *string    `json:"body" swaggertype:"string" example:"<p>Hello world</p>"`
	PublishedAt            *time.Time `json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	Private                bool       `json:"private" example:"true"`
	UserID                 *int64     `json:"-"`
	CreatedAt              *time.Time `json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt              *time.Time `json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	NotifyUsersOnPublished bool       `json:"notify_users_on_published" example:"true"`
	MetaDescription        string     `json:"meta_description" example:"A good meta" validate:"gt=1,lt=60"`
	SsoID                  *uuid.UUID `json:"sso_id" swaggertype:"string" example:"123e4567-e89b-12d3-a456-426614174000"`

	// Categories *[]CategoryModel `json:"categories"`
	// Medias     *[]MediaModel    `json:"medias"`
}

func (a *AlbumUpdateRequest) Validate() error {
	validate = validator.New()

	err := validate.Struct(a)

	return err
}

// AlbumModel represents a single album.
type AlbumRequest struct {
	ID                     int32      `json:"id" example:"1"`
	Slug                   *string    `json:"slug" example:"a-good-album"`
	Title                  string     `json:"title" example:"A good album" validate:"required,lt=60"`
	Body                   *string    `json:"body" swaggertype:"string" example:"<p>Hello world</p>"`
	PublishedAt            *time.Time `json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	Private                bool       `json:"private" example:"true"`
	UserID                 *int64     `json:"-"`
	CreatedAt              *time.Time `json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt              *time.Time `json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	NotifyUsersOnPublished bool       `json:"notify_users_on_published" example:"true"`
	MetaDescription        string     `json:"meta_description" example:"A good meta" validate:"required,gt=1,lt=60"`
	SsoID                  *uuid.UUID `json:"sso_id" swaggertype:"string" example:"123e4567-e89b-12d3-a456-426614174000"`

	// Categories *[]CategoryModel `json:"categories"`
	// Medias     *[]MediaModel    `json:"medias"`
}

func (a *AlbumRequest) Validate() error {
	validate = validator.New()

	err := validate.Struct(a)

	return err
}

type CategoryReponse struct {
	ID   int64  `json:"id" example:"1"`
	Name string `json:"name" example:"A good category"`
}

type CustomPropertiesResponse struct {
	Height int `json:"height"`
	Width  int `json:"width"`
}

type ResponsiveImagesResponse struct {
	Responsive ResponsiveResponse `gorm:"" json:"responsive"`
}
type ResponsiveResponse struct {
	Urls      []string `gorm:"type:text[];" json:"urls"`
	Base64Svg string   `json:"base64svg"`
}

type MediaReponse struct {
	ID       int32   `json:"id" example:"1"`
	Name     string  `json:"name" example:"A good media"`
	FileName string  `json:"file_name" example:"a-good-media.jpg"`
	MimeType *string `json:"mime_type" example:"image/jpeg"`
	Size     int64   `json:"size" example:"1"`
	// Manipulations    pgtype.JSON
	CustomProperties CustomPropertiesResponse `json:"custom_properties"`
	ResponsiveImages ResponsiveImagesResponse `json:"responsive_images"`
	CreatedAt        *time.Time               `json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt        *time.Time               `json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
}

func transformMediaFromDB(media model.Medium) MediaReponse {
	return MediaReponse{
		ID:        media.ID,
		Name:      media.Name,
		FileName:  media.FileName,
		MimeType:  media.MimeType,
		Size:      media.Size,
		CreatedAt: media.CreatedAt,
		UpdatedAt: media.UpdatedAt,
		CustomProperties: CustomPropertiesResponse{
			Height: media.CustomProperties.Height,
			Width:  media.CustomProperties.Width,
		},
		ResponsiveImages: ResponsiveImagesResponse{
			Responsive: ResponsiveResponse{
				Urls:      media.ResponsiveImages.Responsive.Urls,
				Base64Svg: media.ResponsiveImages.Responsive.Base64Svg,
			},
		},
	}
}

func transformCategoryFromDB(c model.Category) CategoryReponse {
	return CategoryReponse{
		ID:   c.ID,
		Name: c.Name,
	}
}

func transformAlbumFromDB(a model.Album) *albumspb.AlbumResponse {
	// var mediasResponse *[]MediaReponse
	// if a.Medias != nil {
	// 	var tmp []MediaReponse
	// 	for _, m := range a.Medias {
	// 		tmp = append(tmp, transformMediaFromDB(m))
	// 	}
	// 	mediasResponse = &tmp
	// }

	// var categoriesResponse *[]CategoryReponse
	// if a.Categories != nil {
	// 	var tmp []CategoryReponse
	// 	for _, c := range a.Categories {
	// 		tmp = append(tmp, transformCategoryFromDB(c))
	// 	}
	// 	categoriesResponse = &tmp
	// }

	var publishedAt *timestamppb.Timestamp
	if a.PublishedAt != nil {
		publishedAt = &timestamppb.Timestamp{Seconds: int64(a.PublishedAt.Second())}
	}

	return &albumspb.AlbumResponse{
		Id:                     int64(a.ID),
		Slug:                   a.Slug,
		Title:                  a.Title,
		MetaDescription:        a.MetaDescription,
		Content:                *a.Body,
		PublishedAt:            publishedAt,
		Private:                a.Private,
		AuthorId:               *a.SsoID,
		UserId:                 a.UserID,
		CreatedAt:              &timestamppb.Timestamp{Seconds: int64(a.CreatedAt.Second())},
		UpdatedAt:              &timestamppb.Timestamp{Seconds: int64(a.UpdatedAt.Second())},
		NotifyUsersOnPublished: a.NotifyUsersOnPublished,
		// Medias:                 mediasResponse,
		// Categories:             categoriesResponse,
	}
}

type AlbumResponse struct {
	ID                     int32      `json:"id" example:"1"`
	Slug                   string     `json:"slug" example:"a-good-album"`
	Title                  string     `json:"title" example:"A good album"`
	Body                   *string    `json:"body" swaggertype:"string" example:"<p>Hello world</p>"`
	PublishedAt            *time.Time `json:"published_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	Private                bool       `json:"private" example:"true"`
	UserID                 *int64     `json:"-"`
	CreatedAt              *time.Time `json:"created_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	UpdatedAt              *time.Time `json:"updated_at" swaggertype:"string" example:"2019-04-19T17:47:28Z"`
	NotifyUsersOnPublished bool       `json:"notify_users_on_published" example:"true"`
	MetaDescription        string     `json:"meta_description" example:"A good meta"`
	SsoID                  *string    `json:"sso_id" swaggertype:"string" example:"123e4567-e89b-12d3-a456-426614174000"`

	Categories *[]CategoryReponse `json:"categories,omitempty"`
	Medias     *[]MediaReponse    `json:"medias,omitempty"`
}
