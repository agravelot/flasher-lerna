#!/bin/bash

declare -a generators=("typescript-fetch")

for i in "${generators[@]}"; do
    echo "$i"
    docker run --rm \
        -u 1000 \
        -v ${PWD}:/src openapitools/openapi-generator-cli generate \
        -i /src/apps/api-go/gen/openapiv2/all.swagger.json \
        -g $i \
        -o /src/apps/http-client/src \
        --additional-properties=packageName=flasher-http-client,supportsES6=true
done
