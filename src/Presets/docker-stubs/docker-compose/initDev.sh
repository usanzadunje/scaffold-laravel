#!/bin/bash

php /var/www/artisan storage:link
php /var/www/artisan clear-optimized
php /var/www/artisan optimize
php /var/www/artisan view:clear
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
