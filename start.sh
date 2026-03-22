#!/bin/sh
set -e

APP_PORT="${PORT:-8080}"
exec php -S 0.0.0.0:"$APP_PORT" -t /app
