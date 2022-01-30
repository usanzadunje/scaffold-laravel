#!/bin/bash

php /var/www/artisan storage:link
php /var/www/artisan optimize
/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
