#!/bin/bash
# Pre-Deploy Command Railway: dijalankan sebelum container aplikasi start.
set -e

php artisan migrate --force

# Idempoten: hanya membuat akun admin & pengaturan bawaan bila belum ada.
php artisan db:seed --force

php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
