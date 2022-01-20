-- name: GetAlbumBySlug :one
SELECT a.id, a.slug, a.title, a.body, a.published_at,a.private, a.user_id, a.created_at, a.updated_at, a.notify_users_on_published, a.meta_description, a.sso_id
FROM albums a
WHERE a.slug = $1 AND (@is_admin::boolean OR published_at < now()) AND (@is_admin::boolean OR private = false);

-- name: GetMediasByAlbumIds :many
SELECT m.id, m.model_id, m.name, m.size, m.created_at, m.updated_at
FROM media m
WHERE m.model_id = ANY($1::int[]) and m.model_type = 'App\Models\Album';

-- name: GetCategoryBySlug :one
SELECT c.id, c.slug, c.name, c.description, c.meta_description, c.created_at, c.updated_at
FROM categories c
WHERE c.slug = $1;

-- name: GetCategoriesByAlbumIds :many
SELECT *
FROM categories c
INNER JOIN album_category ac ON ac.category_id = c.id
WHERE ac.album_id = ANY($1::int[]);

-- name: CountAlbums :one
SELECT count(a.id)
FROM albums a
WHERE (@is_admin::boolean OR published_at < now()) AND (@is_admin::boolean OR private = false);


-- name: GetAlbums :many
SELECT id, slug, title, body, published_at,private, user_id, created_at, updated_at, notify_users_on_published, meta_description, sso_id
FROM albums
WHERE (@is_admin::boolean OR published_at < now()) AND (@is_admin::boolean OR private = false) AND (@id::int = 0 OR id > @id)
ORDER BY published_at DESC
LIMIT $1;

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

-- name: CreateCategory :one
INSERT INTO categories (slug, name, description, meta_description, created_at, updated_at)
VALUES ($1, $2, $3, $4, now(), now())
RETURNING *;

-- name: LinkCategoryToAlbum :exec
INSERT INTO album_category (album_id, category_id, created_at, updated_at)
VALUES ($1, $2, now(), now());


-- name: CreateMedia :one
INSERT INTO media (model_id, model_type, name, size, collection_name, file_name, disk, mime_type, manipulations, custom_properties, responsive_images, created_at, updated_at)
VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, now(), now())
RETURNING *;
