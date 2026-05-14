#!/bin/bash
mkdir -p /var/www/html/public/uploads/orders

chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/database
chown -R www-data:www-data /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/public/uploads

chmod -R 775 /var/www/html/storage
chmod -R 775 /var/www/html/database
chmod -R 775 /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/public/uploads

php artisan migrate --force
php artisan db:seed --force
apache2-foreground