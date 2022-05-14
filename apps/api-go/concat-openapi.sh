#!/bin/bash
OUTPUT_FILE="./openapi.json"

echo "Listing all the available openapi specs"
SPEC=$(find gen/ -type f -name "*.swagger.json")
echo "${SPEC[*]}"

echo "Concatenating all the specs: $OUTPUT_FILE"
jq -s '.[0] * .[1]' ${SPEC} >${OUTPUT_FILE}
