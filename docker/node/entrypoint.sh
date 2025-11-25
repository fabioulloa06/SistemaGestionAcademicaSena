#!/bin/sh
set -euo pipefail

cd /var/www/html

if [ ! -d node_modules ]; then
    npm install
fi

exec npm run dev -- --host 0.0.0.0 --port "${VITE_PORT:-5173}"

