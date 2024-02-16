#! /bin/sh

docker run --rm \
    -v "$(pwd):/opt:z" \
    -v "$(pwd)/docker/development/ssh:/root/.ssh:z" \
    -w /opt \
    laravelsail/php82-composer:latest \
    "$@"