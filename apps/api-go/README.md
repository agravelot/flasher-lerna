# Flasher api

## Requirements

- Golang version 1.18
- Buf installed with his dependencies, (tarball)[https://docs.buf.build/installation#tarball] installation or (homebrew)[https://docs.buf.build/installation#homebrew] is recommended.

## Init database

```bash
goose postgres "user=flasher password=flasher dbname=flasher sslmode=disable" up
```
