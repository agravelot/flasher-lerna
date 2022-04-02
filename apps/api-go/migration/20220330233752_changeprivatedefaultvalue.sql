-- +goose Up
-- +goose StatementBegin
SELECT 'up SQL query';
ALTER TABLE albums ALTER COLUMN private SET DEFAULT FALSE;
-- +goose StatementEnd

-- +goose Down
-- +goose StatementBegin
SELECT 'down SQL query';
ALTER TABLE albums ALTER COLUMN private SET DEFAULT TRUE;
-- +goose StatementEnd
