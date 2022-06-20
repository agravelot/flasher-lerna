#!/bin/bash

declare -a generators=("typescript-fetch")

for i in "${generators[@]}"; do
    echo "$i"
    docker run --rm \
        -u 1000 \
        -v ${PWD}:/src openapitools/openapi-generator-cli generate \
        -i /src/apps/api-go/third_party/OpenAPI/flasher.swagger.json \
        -g $i \
        -o /src/apps/http-client/src
done
