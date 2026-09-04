#!/usr/bin/env bash
# Start the cart app locally on the 2016 stack.
# PHP 7.4 is keg-only (Homebrew), so it is not on the default PATH.
set -e

PHP=/opt/homebrew/opt/php@7.4/bin/php
cd "$(dirname "$0")"

# MySQL must be running: brew services start mysql
# PHP_CLI_SERVER_WORKERS lets the built-in server handle the page's parallel
# asset requests. Each browser connection holds a worker for as long as it is
# kept alive, so this needs plenty of headroom or whole page loads stall.
PHP_CLI_SERVER_WORKERS=32 "$PHP" artisan serve --host=127.0.0.1 --port=8000
