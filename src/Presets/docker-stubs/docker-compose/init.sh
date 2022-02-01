#!/bin/bash

php /var/www/artisan storage:link
php /var/www/artisan clear-compiled
php /var/www/artisan optimize
php /var/www/artisan view:cache
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
