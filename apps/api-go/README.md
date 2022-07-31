# Flasher api

[![codecov](https://codecov.io/gh/agravelot/flasher-lerna/branch/main/graph/badge.svg?token=B3HIJ1UON1)](https://codecov.io/gh/agravelot/flasher-lerna)

## Requirements

- Golang version 1.18
- Buf installed with his dependencies, (tarball)[https://docs.buf.build/installation#tarball] installation or (homebrew)[https://docs.buf.build/installation#homebrew] is recommended.

## Init database

```bash
goose postgres "user=flasher password=flasher dbname=flasher sslmode=disable" up
```
