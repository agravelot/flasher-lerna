-- name: GetAlbumBySlug :one
SELECT a.id, a.slug, a.title, a.body, a.published_at,a.private, a.user_id, a.created_at, a.updated_at, a.notify_users_on_published, a.meta_description, a.sso_id
FROM albums a
WHERE a.slug = $1;

-- name: GetMediasByAlbumIds :many
SELECT m.id, m.model_id, m.name, m.size, m.created_at, m.updated_at
FROM media m
WHERE m.model_id = ANY($1::int[]) and m.model_type = 'App\Models\Album';

-- name: GetCategoryBySlug :one
SELECT c.id, c.slug, c.name, c.description, c.meta_description, c.created_at, c.updated_at
FROM categories c
WHERE c.slug = $1;

-- name: GetCategoriesByAlbumIds :many
SELECT c.id, c.slug, c.name, c.description, c.meta_description, c.created_at, c.updated_at
FROM categories c
INNER JOIN categorizables ci ON ci.category_id = c.id
WHERE ci.categorizable_id = ANY($1::int[]) AND ci.categorizable_type = 'App\Models\Album';

-- name: CountPublishedAlbums :one
SELECT count(a.id)
FROM albums a
WHERE a.published_at < now() AND private = false;

-- name: GetPublishedAlbums :many
SELECT a.id, a.slug, a.title, a.body, a.published_at,a.private, a.user_id, a.created_at, a.updated_at, a.notify_users_on_published, a.meta_description, a.sso_id
FROM albums a
WHERE a.published_at < now() AND private = false
ORDER BY a.published_at DESC
LIMIT $1;

-- name: GetPublishedAlbumsAfterID :many
SELECT a.id, a.slug, a.title, a.body, a.published_at,a.private, a.user_id, a.created_at, a.updated_at, a.notify_users_on_published, a.meta_description, a.sso_id
FROM albums a
WHERE a.published_at < now() AND private = false AND a.id > $1
ORDER BY a.published_at DESC
LIMIT $2;


-- name: CreateAlbum :one
INSERT INTO albums (slug, title, body, private, meta_description, sso_id, published_at, created_at, updated_at)
VALUES ($1, $2, $3, $4, $5, $6, $7, now(), now())
RETURNING *;

-- name: UpdateAlbum :exec
UPDATE albums
SET slug = $1, title = $2, body = $3, private = $4, meta_description = $5, sso_id = $6, published_at = $7, updated_at = NOW()
WHERE slug = $8;

-- name: DeleteAlbum :exec
DELETE FROM albums
WHERE slug = $1;
