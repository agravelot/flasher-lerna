-- +goose Up
-- +goose StatementBegin
SELECT 'up SQL query';
ALTER TABLE "albums" ADD COLUMN "is_published_publicly" BOOLEAN NOT NULL DEFAULT FALSE;
UPDATE "albums" SET "is_published_publicly" = TRUE WHERE "private" = FALSE;
-- +goose StatementEnd

-- +goose Down
-- +goose StatementBegin
SELECT 'down SQL query';
ALTER TABLE "albums" DROP COLUMN "is_published_publicly";
-- +goose StatementEnd
