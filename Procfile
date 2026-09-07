web: php -S 0.0.0.0:${PORT:-8000} -t public
release: php artisan db:wipe --force && php artisan migrate --seed --force
