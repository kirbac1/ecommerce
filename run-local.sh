#!/usr/bin/env bash
# Start the app locally.
#
# Laravel 13 needs PHP 8.3+. Set PHP to override the binary; otherwise this
# tries the Homebrew keg-only install (which is deliberately off PATH) and
# falls back to whatever `php` is available, so the same script works on a
# dev machine and on a CI runner.
set -euo pipefail

cd "$(dirname "$0")"

if [ -z "${PHP:-}" ]; then
    for candidate in \
        /opt/homebrew/opt/php@8.3/bin/php \
        /usr/local/opt/php@8.3/bin/php \
        "$(command -v php || true)"
    do
        if [ -n "$candidate" ] && [ -x "$candidate" ]; then
            PHP="$candidate"
            break
        fi
    done
fi

if [ -z "${PHP:-}" ]; then
    echo "No PHP binary found. Install PHP 8.3 (see readme.md) or set PHP=/path/to/php." >&2
    exit 1
fi

HOST="${HOST:-127.0.0.1}"
PORT="${PORT:-8000}"

# MySQL must be running: brew services start mysql
#
# PHP_CLI_SERVER_WORKERS lets the built-in server handle the page's parallel
# asset requests. Each browser connection holds a worker for as long as it is
# kept alive, so this needs plenty of headroom or whole page loads stall.
export PHP_CLI_SERVER_WORKERS="${PHP_CLI_SERVER_WORKERS:-32}"

exec "$PHP" artisan serve --host="$HOST" --port="$PORT"
