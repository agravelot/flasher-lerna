// Code generated by sqlc. DO NOT EDIT.
// source: query.sql

package tutorial

import (
	"context"
	"database/sql"

	"github.com/google/uuid"
	"github.com/jackc/pgtype"
)

const countAlbums = `-- name: CountAlbums :one
SELECT count(a.id)
FROM albums a
WHERE ($1::boolean OR published_at < now()) AND ($1::boolean OR private = false)
`

func (q *Queries) CountAlbums(ctx context.Context, isAdmin bool) (int64, error) {
	row := q.db.QueryRow(ctx, countAlbums, isAdmin)
	var count int64
	err := row.Scan(&count)
	return count, err
}

const createAlbum = `-- name: CreateAlbum :one
INSERT INTO albums (slug, title, body, private, meta_description, sso_id, published_at, created_at, updated_at)
VALUES ($1, $2, $3, $4, $5, $6, $7, now(), now())
RETURNING id, slug, title, body, published_at, private, user_id, created_at, updated_at, notify_users_on_published, meta_description, sso_id
`

type CreateAlbumParams struct {
	Slug            string
	Title           string
	Body            sql.NullString
	Private         bool
	MetaDescription string
	SsoID           uuid.NullUUID
	PublishedAt     sql.NullTime
}

func (q *Queries) CreateAlbum(ctx context.Context, arg CreateAlbumParams) (Album, error) {
	row := q.db.QueryRow(ctx, createAlbum,
		arg.Slug,
		arg.Title,
		arg.Body,
		arg.Private,
		arg.MetaDescription,
		arg.SsoID,
		arg.PublishedAt,
	)
	var i Album
	err := row.Scan(
		&i.ID,
		&i.Slug,
		&i.Title,
		&i.Body,
		&i.PublishedAt,
		&i.Private,
		&i.UserID,
		&i.CreatedAt,
		&i.UpdatedAt,
		&i.NotifyUsersOnPublished,
		&i.MetaDescription,
		&i.SsoID,
	)
	return i, err
}

const createCategory = `-- name: CreateCategory :one
INSERT INTO categories (slug, name, description, meta_description, created_at, updated_at)
VALUES ($1, $2, $3, $4, now(), now())
RETURNING id, name, slug, description, created_at, updated_at, meta_description
`

type CreateCategoryParams struct {
	Slug            string
	Name            string
	Description     sql.NullString
	MetaDescription string
}

func (q *Queries) CreateCategory(ctx context.Context, arg CreateCategoryParams) (Category, error) {
	row := q.db.QueryRow(ctx, createCategory,
		arg.Slug,
		arg.Name,
		arg.Description,
		arg.MetaDescription,
	)
	var i Category
	err := row.Scan(
		&i.ID,
		&i.Name,
		&i.Slug,
		&i.Description,
		&i.CreatedAt,
		&i.UpdatedAt,
		&i.MetaDescription,
	)
	return i, err
}

const createMedia = `-- name: CreateMedia :one
INSERT INTO media (model_id, model_type, name, size, collection_name, file_name, disk, mime_type, manipulations, custom_properties, responsive_images, created_at, updated_at)
VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, now(), now())
RETURNING id, model_type, model_id, collection_name, name, file_name, mime_type, disk, size, manipulations, custom_properties, responsive_images, order_column, created_at, updated_at, uuid, conversions_disk
`

type CreateMediaParams struct {
	ModelID          int64
	ModelType        string
	Name             string
	Size             int64
	CollectionName   string
	FileName         string
	Disk             string
	MimeType         sql.NullString
	Manipulations    pgtype.JSON
	CustomProperties pgtype.JSON
	ResponsiveImages pgtype.JSON
}

func (q *Queries) CreateMedia(ctx context.Context, arg CreateMediaParams) (Medium, error) {
	row := q.db.QueryRow(ctx, createMedia,
		arg.ModelID,
		arg.ModelType,
		arg.Name,
		arg.Size,
		arg.CollectionName,
		arg.FileName,
		arg.Disk,
		arg.MimeType,
		arg.Manipulations,
		arg.CustomProperties,
		arg.ResponsiveImages,
	)
	var i Medium
	err := row.Scan(
		&i.ID,
		&i.ModelType,
		&i.ModelID,
		&i.CollectionName,
		&i.Name,
		&i.FileName,
		&i.MimeType,
		&i.Disk,
		&i.Size,
		&i.Manipulations,
		&i.CustomProperties,
		&i.ResponsiveImages,
		&i.OrderColumn,
		&i.CreatedAt,
		&i.UpdatedAt,
		&i.Uuid,
		&i.ConversionsDisk,
	)
	return i, err
}

const deleteAlbum = `-- name: DeleteAlbum :exec
DELETE FROM albums
WHERE slug = $1
`

func (q *Queries) DeleteAlbum(ctx context.Context, slug string) error {
	_, err := q.db.Exec(ctx, deleteAlbum, slug)
	return err
}

const getAlbumBySlug = `-- name: GetAlbumBySlug :one
SELECT a.id, a.slug, a.title, a.body, a.published_at,a.private, a.user_id, a.created_at, a.updated_at, a.notify_users_on_published, a.meta_description, a.sso_id
FROM albums a
WHERE a.slug = $1 AND ($2::boolean OR published_at < now()) AND ($2::boolean OR private = false)
`

type GetAlbumBySlugParams struct {
	Slug    string
	IsAdmin bool
}

func (q *Queries) GetAlbumBySlug(ctx context.Context, arg GetAlbumBySlugParams) (Album, error) {
	row := q.db.QueryRow(ctx, getAlbumBySlug, arg.Slug, arg.IsAdmin)
	var i Album
	err := row.Scan(
		&i.ID,
		&i.Slug,
		&i.Title,
		&i.Body,
		&i.PublishedAt,
		&i.Private,
		&i.UserID,
		&i.CreatedAt,
		&i.UpdatedAt,
		&i.NotifyUsersOnPublished,
		&i.MetaDescription,
		&i.SsoID,
	)
	return i, err
}

const getAlbums = `-- name: GetAlbums :many
SELECT id, slug, title, body, published_at,private, user_id, created_at, updated_at, notify_users_on_published, meta_description, sso_id
FROM albums
WHERE ($2::boolean OR published_at < now()) AND ($2::boolean OR private = false) AND ($3::int = 0 OR id > $3)
ORDER BY published_at DESC
LIMIT $1
`

type GetAlbumsParams struct {
	Limit   int32
	IsAdmin bool
	ID      int32
}

func (q *Queries) GetAlbums(ctx context.Context, arg GetAlbumsParams) ([]Album, error) {
	rows, err := q.db.Query(ctx, getAlbums, arg.Limit, arg.IsAdmin, arg.ID)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	items := []Album{}
	for rows.Next() {
		var i Album
		if err := rows.Scan(
			&i.ID,
			&i.Slug,
			&i.Title,
			&i.Body,
			&i.PublishedAt,
			&i.Private,
			&i.UserID,
			&i.CreatedAt,
			&i.UpdatedAt,
			&i.NotifyUsersOnPublished,
			&i.MetaDescription,
			&i.SsoID,
		); err != nil {
			return nil, err
		}
		items = append(items, i)
	}
	if err := rows.Err(); err != nil {
		return nil, err
	}
	return items, nil
}

const getCategoriesByAlbumIds = `-- name: GetCategoriesByAlbumIds :many
SELECT c.id, name, slug, description, c.created_at, c.updated_at, meta_description, ac.id, album_id, category_id, ac.created_at, ac.updated_at
FROM categories c
INNER JOIN album_category ac ON ac.category_id = c.id
WHERE ac.album_id = ANY($1::int[])
`

type GetCategoriesByAlbumIdsRow struct {
	ID              int32
	Name            string
	Slug            string
	Description     sql.NullString
	CreatedAt       sql.NullTime
	UpdatedAt       sql.NullTime
	MetaDescription string
	ID_2            int32
	AlbumID         int32
	CategoryID      int32
	CreatedAt_2     sql.NullTime
	UpdatedAt_2     sql.NullTime
}

func (q *Queries) GetCategoriesByAlbumIds(ctx context.Context, dollar_1 []int32) ([]GetCategoriesByAlbumIdsRow, error) {
	rows, err := q.db.Query(ctx, getCategoriesByAlbumIds, dollar_1)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	items := []GetCategoriesByAlbumIdsRow{}
	for rows.Next() {
		var i GetCategoriesByAlbumIdsRow
		if err := rows.Scan(
			&i.ID,
			&i.Name,
			&i.Slug,
			&i.Description,
			&i.CreatedAt,
			&i.UpdatedAt,
			&i.MetaDescription,
			&i.ID_2,
			&i.AlbumID,
			&i.CategoryID,
			&i.CreatedAt_2,
			&i.UpdatedAt_2,
		); err != nil {
			return nil, err
		}
		items = append(items, i)
	}
	if err := rows.Err(); err != nil {
		return nil, err
	}
	return items, nil
}

const getCategoryBySlug = `-- name: GetCategoryBySlug :one
SELECT c.id, c.slug, c.name, c.description, c.meta_description, c.created_at, c.updated_at
FROM categories c
WHERE c.slug = $1
`

type GetCategoryBySlugRow struct {
	ID              int32
	Slug            string
	Name            string
	Description     sql.NullString
	MetaDescription string
	CreatedAt       sql.NullTime
	UpdatedAt       sql.NullTime
}

func (q *Queries) GetCategoryBySlug(ctx context.Context, slug string) (GetCategoryBySlugRow, error) {
	row := q.db.QueryRow(ctx, getCategoryBySlug, slug)
	var i GetCategoryBySlugRow
	err := row.Scan(
		&i.ID,
		&i.Slug,
		&i.Name,
		&i.Description,
		&i.MetaDescription,
		&i.CreatedAt,
		&i.UpdatedAt,
	)
	return i, err
}

const getMediasByAlbumIds = `-- name: GetMediasByAlbumIds :many
SELECT id, model_type, model_id, collection_name, name, file_name, mime_type, disk, size, manipulations, custom_properties, responsive_images, order_column, created_at, updated_at, uuid, conversions_disk
FROM media m
WHERE m.model_id = ANY($1::int[]) AND m.model_type = 'App\Models\Album'
`

func (q *Queries) GetMediasByAlbumIds(ctx context.Context, dollar_1 []int32) ([]Medium, error) {
	rows, err := q.db.Query(ctx, getMediasByAlbumIds, dollar_1)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	items := []Medium{}
	for rows.Next() {
		var i Medium
		if err := rows.Scan(
			&i.ID,
			&i.ModelType,
			&i.ModelID,
			&i.CollectionName,
			&i.Name,
			&i.FileName,
			&i.MimeType,
			&i.Disk,
			&i.Size,
			&i.Manipulations,
			&i.CustomProperties,
			&i.ResponsiveImages,
			&i.OrderColumn,
			&i.CreatedAt,
			&i.UpdatedAt,
			&i.Uuid,
			&i.ConversionsDisk,
		); err != nil {
			return nil, err
		}
		items = append(items, i)
	}
	if err := rows.Err(); err != nil {
		return nil, err
	}
	return items, nil
}

const linkCategoryToAlbum = `-- name: LinkCategoryToAlbum :exec
INSERT INTO album_category (album_id, category_id, created_at, updated_at)
VALUES ($1, $2, now(), now())
`

type LinkCategoryToAlbumParams struct {
	AlbumID    int32
	CategoryID int32
}

func (q *Queries) LinkCategoryToAlbum(ctx context.Context, arg LinkCategoryToAlbumParams) error {
	_, err := q.db.Exec(ctx, linkCategoryToAlbum, arg.AlbumID, arg.CategoryID)
	return err
}

const updateAlbum = `-- name: UpdateAlbum :exec
UPDATE albums
SET slug = $1, title = $2, body = $3, private = $4, meta_description = $5, sso_id = $6, published_at = $7, updated_at = NOW()
WHERE slug = $8
`

type UpdateAlbumParams struct {
	Slug            string
	Title           string
	Body            sql.NullString
	Private         bool
	MetaDescription string
	SsoID           uuid.NullUUID
	PublishedAt     sql.NullTime
	Slug_2          string
}

func (q *Queries) UpdateAlbum(ctx context.Context, arg UpdateAlbumParams) error {
	_, err := q.db.Exec(ctx, updateAlbum,
		arg.Slug,
		arg.Title,
		arg.Body,
		arg.Private,
		arg.MetaDescription,
		arg.SsoID,
		arg.PublishedAt,
		arg.Slug_2,
	)
	return err
}
