#!/bin/bash
set -euo pipefail
go test -json -v ./... 2>&1 | tee /tmp/gotest.log | gotestfmt
