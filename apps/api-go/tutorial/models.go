// Code generated by sqlc. DO NOT EDIT.

package tutorial

import (
	"database/sql"
	"encoding/json"
	"time"

	"github.com/google/uuid"
)

type Album struct {
	ID                     int32
	Slug                   string
	Title                  string
	Body                   sql.NullString
	PublishedAt            sql.NullTime
	Private                bool
	UserID                 sql.NullInt64
	CreatedAt              sql.NullTime
	UpdatedAt              sql.NullTime
	NotifyUsersOnPublished bool
	MetaDescription        string
	SsoID                  uuid.NullUUID
}

type AlbumCategory struct {
	ID         int32
	AlbumID    int32
	CategoryID int32
	CreatedAt  sql.NullTime
	UpdatedAt  sql.NullTime
}

type AlbumCosplayer struct {
	ID          int32
	AlbumID     sql.NullInt32
	CosplayerID sql.NullInt32
	CreatedAt   sql.NullTime
	UpdatedAt   sql.NullTime
}

type Article struct {
	ID              int64
	Slug            sql.NullString
	Name            sql.NullString
	MetaDescription sql.NullString
	Content         sql.NullString
	AuthorUuid      sql.NullString
	PublishedAt     sql.NullTime
	CreatedAt       sql.NullTime
	UpdatedAt       sql.NullTime
	DeletedAt       sql.NullTime
}

type Categorizable struct {
	CategoryID        int32
	CategorizableType string
	CategorizableID   int64
	CreatedAt         sql.NullTime
	UpdatedAt         sql.NullTime
}

type Category struct {
	ID              int32
	Name            string
	Slug            string
	Description     sql.NullString
	CreatedAt       sql.NullTime
	UpdatedAt       sql.NullTime
	MetaDescription string
}

type Comment struct {
	ID              int32
	CreatedAt       sql.NullTime
	UpdatedAt       sql.NullTime
	UserID          sql.NullInt64
	CommentableID   int32
	CommentableType string
	Body            string
	SsoID           uuid.UUID
}

type Contact struct {
	ID        int32
	Name      string
	Email     string
	Message   string
	UserID    sql.NullInt64
	CreatedAt sql.NullTime
	UpdatedAt sql.NullTime
	SsoID     uuid.NullUUID
}

type Cosplayer struct {
	ID          int32
	Name        string
	Slug        string
	Description sql.NullString
	Picture     sql.NullString
	UserID      sql.NullInt64
	CreatedAt   sql.NullTime
	UpdatedAt   sql.NullTime
	SsoID       uuid.NullUUID
}

type FailedJob struct {
	ID         int64
	Connection string
	Queue      string
	Payload    string
	Exception  string
	FailedAt   time.Time
}

type InformationSchemaTable struct {
	TableName string
}

type Invitation struct {
	OldID       int64
	CosplayerID int64
	Email       string
	Message     sql.NullString
	CreatedAt   sql.NullTime
	UpdatedAt   sql.NullTime
	Token       string
	ConfirmedAt sql.NullTime
	ID          uuid.UUID
}

type Medium struct {
	ID               int32
	ModelType        string
	ModelID          int64
	CollectionName   string
	Name             string
	FileName         string
	MimeType         sql.NullString
	Disk             string
	Size             int64
	Manipulations    json.RawMessage
	CustomProperties json.RawMessage
	ResponsiveImages json.RawMessage
	OrderColumn      sql.NullInt32
	CreatedAt        sql.NullTime
	UpdatedAt        sql.NullTime
	Uuid             uuid.NullUUID
	ConversionsDisk  sql.NullString
}

type Migration struct {
	ID        int32
	Migration string
	Batch     int32
}

type Page struct {
	ID          int64
	Name        string
	Title       string
	Description sql.NullString
	CreatedAt   sql.NullTime
	UpdatedAt   sql.NullTime
}

type Post struct {
	ID        int32
	Title     string
	Slug      string
	SeoTitle  sql.NullString
	Body      string
	Active    bool
	UserID    sql.NullInt64
	CreatedAt sql.NullTime
	UpdatedAt sql.NullTime
	SsoID     uuid.UUID
}

type Setting struct {
	Name        string
	Value       sql.NullString
	Title       string
	Description sql.NullString
	CreatedAt   sql.NullTime
	UpdatedAt   sql.NullTime
	Type        string
	ID          int64
}

type SocialMedium struct {
	ID        int32
	Name      string
	Url       string
	Icon      string
	Color     string
	Active    bool
	CreatedAt sql.NullTime
	UpdatedAt sql.NullTime
}

type Testimonial struct {
	ID          int32
	Name        string
	Email       string
	Body        string
	PublishedAt sql.NullTime
	UserID      sql.NullInt64
	CreatedAt   sql.NullTime
	UpdatedAt   sql.NullTime
	SsoID       uuid.NullUUID
}

type User struct {
	ID              int32
	Name            string
	Email           string
	Password        string
	Role            string
	EmailVerifiedAt sql.NullTime
	RememberToken   sql.NullString
	CreatedAt       sql.NullTime
	UpdatedAt       sql.NullTime
}
