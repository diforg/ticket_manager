#!/bin/sh
set -e

if [ -d /var/www/html/storage ]; then
  chown -R www-data:www-data /var/www/html/storage || true
  chmod -R u+rwX,g+rwX /var/www/html/storage || true
fi

if [ -d /var/www/html/bootstrap/cache ]; then
  chown -R www-data:www-data /var/www/html/bootstrap/cache || true
  chmod -R u+rwX,g+rwX /var/www/html/bootstrap/cache || true
fi

exec "$@"
