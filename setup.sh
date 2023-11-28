#!/usr/bin/env bash
set -euo pipefail

docker run --rm --volumes-from wordpress --network container:wordpress --user 33:33 \
  -e WORDPRESS_DB_HOST=db \
  -e WORDPRESS_DB_USER=wordpress \
  -e WORDPRESS_DB_PASSWORD=wordpress \
  -e WORDPRESS_DB_NAME=wordpress \
  -v './setup-in-docker.sh:/usr/src/setup.sh' \
  wordpress:cli bash /usr/src/setup.sh
