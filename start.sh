#!/bin/sh
set -e

APP_PORT="${PORT:-8080}"

if [ -z "$APP_PORT" ] || [ "$APP_PORT" = '${PORT:-8080}' ]; then
	APP_PORT="8080"
fi

exec php -S 0.0.0.0:"$APP_PORT" -t /app
