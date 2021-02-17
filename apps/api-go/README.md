curl "https://s3.fr-par.scw.cloud/backups.jkanda.fr/postgres/all_2021-02-16T00%3A00%3A00Z.sql.gz?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=SCW7RGHMF6TNSYRE0P0F%2F20210216%2Ffr-par%2Fs3%2Faws4_request&X-Amz-Date=20210216T190921Z&X-Amz-Expires=3591&X-Amz-Signature=4073fd79aa4fa96f1b1807f4ad9e44015ec6d86d7a7cee86a7acfe6a6cf115ae&X-Amz-SignedHeaders=host" | gunzip | psql -U flasher -d flasher

"host=127.0.0.1 port=5432 user=flasher dbname=flasher password=flasher sslmode=disable"

gen --sqltype=postgres \
   	--connstr "host=127.0.0.1 port=5432 user=flasher dbname=flasher password=flasher sslmode=disable" \
   	--database flasher  \
   	--json \
   	--gorm \
   	--guregu \
   	--rest \
   	--out ./example \
   	--module github.com/agravelot/flasher \
   	--mod \
   	--server \
   	--makefile \
   	--json-fmt=snake \
   	--generate-dao \
   	--generate-proj \
   	--overwrite

gen --sqltype=postgres \
   	--connstr "host=127.0.0.1 port=5432 user=flasher dbname=flasher password=flasher sslmode=disable" \
   	--database flasher  \
   	--json \
   	--gorm \
   	--guregu \
   	--out ./apps/api-go/example \
   	--module github.com/agravelot/flasher \
   	--mod \
   	--server \
   	--makefile \
   	--json-fmt=snake \
   	--generate-proj \
   	--overwrite \
    --exclude=telescope_entries,telescope_entries_tags,telescope_monitoring,users_save,failed_jobs