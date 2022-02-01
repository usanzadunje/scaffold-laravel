#!/bin/bash

php /var/www/artisan storage:link
php /var/www/artisan clear-compiled
php /var/www/artisan optimize
php /var/www/artisan view:clear
php /var/www/artisan serve --host=0.0.0.0 --port=80
