#!/usr/bin/env bash

set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ROOT_DIR="$(cd "${SCRIPT_DIR}/.." && pwd)"

ENV_FILE="${ROOT_DIR}/.env.local"
if [[ -f "${ENV_FILE}" ]]; then
  # shellcheck disable=SC1090
  source "${ENV_FILE}"
fi

command -v php >/dev/null 2>&1 || {
  echo "error: php is not available on PATH. Install PHP 8+ (e.g. via Homebrew) first." >&2
  exit 1
}

PORT="${WP_PORT:-8000}"
HOST="${WP_HOST:-127.0.0.1}"

PHP_FLAGS=${PHP_FLAGS:-"-d opcache.enable_cli=1 -d opcache.enable=1 -d opcache.memory_consumption=128 -d opcache.max_accelerated_files=20000 -d opcache.validate_timestamps=1 -d opcache.revalidate_freq=2"}

export WORDPRESS_DB_NAME="${WORDPRESS_DB_NAME:-wordpress}"
export WORDPRESS_DB_USER="${WORDPRESS_DB_USER:-wordpress}"
export WORDPRESS_DB_PASSWORD="${WORDPRESS_DB_PASSWORD:-wordpress}"
export WORDPRESS_DB_HOST="${WORDPRESS_DB_HOST:-127.0.0.1}"
export WORDPRESS_TABLE_PREFIX="${WORDPRESS_TABLE_PREFIX:-wp_}"

echo "Starting WordPress dev server at http://${HOST}:${PORT}"
echo "Press Ctrl+C to stop."

cd "${ROOT_DIR}"
exec php ${PHP_FLAGS} -S "${HOST}:${PORT}" -t wordpress
