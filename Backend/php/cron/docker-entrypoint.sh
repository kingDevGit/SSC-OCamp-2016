#!/bin/bash

set -eo pipefail

echo "APP_TIMEZONE=\"$APP_TIMEZONE\"" > /var/www/.env
echo "APP_KEY=\"$APP_KEY\"" >> /var/www/.env
echo "APP_ENV=\"$APP_ENV\"" >> /var/www/.env
echo "APP_DEBUG=\"$APP_DEBUG\"" >> /var/www/.env

exec "$@"
