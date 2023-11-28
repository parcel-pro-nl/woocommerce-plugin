#!/usr/bin/env bash
set -euo pipefail

zipPath=$(realpath $1)
echo "Installing WooCommerce from $zipPath"
docker run --rm --volumes-from wordpress --network container:wordpress --user 33:33 \
  -e WORDPRESS_DB_HOST=db \
  -e WORDPRESS_DB_USER=wordpress \
  -e WORDPRESS_DB_PASSWORD=wordpress \
  -e WORDPRESS_DB_NAME=wordpress \
  -v "$zipPath:/usr/src/woocommerce.zip" \
  wordpress:cli \
  wp plugin install /usr/src/woocommerce.zip --activate --force
