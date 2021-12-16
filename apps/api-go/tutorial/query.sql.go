// Code generated by sqlc. DO NOT EDIT.
// source: query.sql

package tutorial

import (
	"context"
	"database/sql"

	"github.com/google/uuid"
)

const countAlbums = `-- name: CountAlbums :one
SELECT count(a.id)
FROM albums a
`

func (q *Queries) CountAlbums(ctx context.Context) (int64, error) {
	row := q.db.QueryRow(ctx, countAlbums)
	var count int64
	err := row.Scan(&count)
	return count, err
}

const countPublishedAlbums = `-- name: CountPublishedAlbums :one
SELECT count(a.id)
FROM albums a
WHERE a.published_at < now() AND private = false
`

func (q *Queries) CountPublishedAlbums(ctx context.Context) (int64, error) {
	row := q.db.QueryRow(ctx, countPublishedAlbums)
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
WHERE a.slug = $1
`

func (q *Queries) GetAlbumBySlug(ctx context.Context, slug string) (Album, error) {
	row := q.db.QueryRow(ctx, getAlbumBySlug, slug)
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
SELECT a.id, a.slug, a.title, a.body, a.published_at,a.private, a.user_id, a.created_at, a.updated_at, a.notify_users_on_published, a.meta_description, a.sso_id
FROM albums a
ORDER BY a.published_at DESC
LIMIT $1
`

func (q *Queries) GetAlbums(ctx context.Context, limit int32) ([]Album, error) {
	rows, err := q.db.Query(ctx, getAlbums, limit)
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

const getAlbumsAfterID = `-- name: GetAlbumsAfterID :many
SELECT a.id, a.slug, a.title, a.body, a.published_at,a.private, a.user_id, a.created_at, a.updated_at, a.notify_users_on_published, a.meta_description, a.sso_id
FROM albums a
WHERE a.id > $1
ORDER BY a.published_at DESC
LIMIT $2
`

type GetAlbumsAfterIDParams struct {
	ID    int32
	Limit int32
}

func (q *Queries) GetAlbumsAfterID(ctx context.Context, arg GetAlbumsAfterIDParams) ([]Album, error) {
	rows, err := q.db.Query(ctx, getAlbumsAfterID, arg.ID, arg.Limit)
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
SELECT c.id, c.slug, c.name, c.description, c.meta_description, c.created_at, c.updated_at
FROM categories c
INNER JOIN categorizables ci ON ci.category_id = c.id
WHERE ci.categorizable_id = ANY($1::int[]) AND ci.categorizable_type = 'App\Models\Album'
`

type GetCategoriesByAlbumIdsRow struct {
	ID              int32
	Slug            string
	Name            string
	Description     sql.NullString
	MetaDescription string
	CreatedAt       sql.NullTime
	UpdatedAt       sql.NullTime
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
			&i.Slug,
			&i.Name,
			&i.Description,
			&i.MetaDescription,
			&i.CreatedAt,
			&i.UpdatedAt,
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
SELECT m.id, m.model_id, m.name, m.size, m.created_at, m.updated_at
FROM media m
WHERE m.model_id = ANY($1::int[]) and m.model_type = 'App\Models\Album'
`

type GetMediasByAlbumIdsRow struct {
	ID        int32
	ModelID   int64
	Name      string
	Size      int64
	CreatedAt sql.NullTime
	UpdatedAt sql.NullTime
}

func (q *Queries) GetMediasByAlbumIds(ctx context.Context, dollar_1 []int32) ([]GetMediasByAlbumIdsRow, error) {
	rows, err := q.db.Query(ctx, getMediasByAlbumIds, dollar_1)
	if err != nil {
		return nil, err
	}
	defer rows.Close()
	items := []GetMediasByAlbumIdsRow{}
	for rows.Next() {
		var i GetMediasByAlbumIdsRow
		if err := rows.Scan(
			&i.ID,
			&i.ModelID,
			&i.Name,
			&i.Size,
			&i.CreatedAt,
			&i.UpdatedAt,
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

const getPublishedAlbums = `-- name: GetPublishedAlbums :many
SELECT a.id, a.slug, a.title, a.body, a.published_at,a.private, a.user_id, a.created_at, a.updated_at, a.notify_users_on_published, a.meta_description, a.sso_id
FROM albums a
WHERE a.published_at < now() AND private = false
ORDER BY a.published_at DESC
LIMIT $1
`

func (q *Queries) GetPublishedAlbums(ctx context.Context, limit int32) ([]Album, error) {
	rows, err := q.db.Query(ctx, getPublishedAlbums, limit)
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

const getPublishedAlbumsAfterID = `-- name: GetPublishedAlbumsAfterID :many
SELECT a.id, a.slug, a.title, a.body, a.published_at,a.private, a.user_id, a.created_at, a.updated_at, a.notify_users_on_published, a.meta_description, a.sso_id
FROM albums a
WHERE a.published_at < now() AND private = false AND a.id > $1
ORDER BY a.published_at DESC
LIMIT $2
`

type GetPublishedAlbumsAfterIDParams struct {
	ID    int32
	Limit int32
}

func (q *Queries) GetPublishedAlbumsAfterID(ctx context.Context, arg GetPublishedAlbumsAfterIDParams) ([]Album, error) {
	rows, err := q.db.Query(ctx, getPublishedAlbumsAfterID, arg.ID, arg.Limit)
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