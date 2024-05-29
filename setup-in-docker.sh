#!/usr/bin/env bash
set -euo pipefail

# Install the WordPress core.
wp core install --url=http://localhost --title=ParcelProDev --admin_user=admin --admin_password=parcelpro1 --admin_email=parcelpro@example.com

# Enable debug logging.
wp config set WP_DEBUG true --raw
wp config set WP_DEBUG_LOG true --raw

# Ensure post ids are always unique (required for Parcel Pro orders).
echo "alter table wp_posts auto_increment=$(date +%s)" | mysql -h db -u wordpress --password=wordpress wordpress

# Install WooCommerce.
if [ -n "${1+x}" ]
then
  wp plugin install woocommerce --activate --force --version="$1"
else
  wp plugin install woocommerce --activate --force
fi

# Preset some WooCommerce settings.
wp option set woocommerce_default_country NL
# Skip the onboarding wizard.
wp option set woocommerce_onboarding_profile '{"skipped":true}' --format=json
# Dismiss reminders and notices.
wp option set woocommerce_task_list_reminder_bar_hidden yes
wp user meta set admin dismissed_no_secure_connection_notice 1
# Show all columns on the orders page.
wp user meta set admin managewoocommerce_page_wc-orderscolumnshidden '[]' --format=json
wp user meta set admin manageedit-shop_ordercolumnshidden '[]' --format=json

# Activate the Parcel Pro plugin.
wp plugin activate parcelpro

# Configure the Parcel Pro plugin.
wp option patch update woocommerce_parcelpro_shipping_settings enabled yes
# If a .env file exists, read it to set configuration.
envFile="$(realpath $(dirname $0))/.env"
if [ -f "$envFile" ]
then
  # Read the .env file into variables.
  while IFS=\= read -r key value
  do
    declare "$key=$value"
  done < "$envFile"

  wp option patch update woocommerce_parcelpro_shipping_settings login_id "$PP_ID"
  wp option patch update woocommerce_parcelpro_shipping_settings api_id "$PP_API_KEY"
fi
