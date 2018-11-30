#!/bin/bash
set -e
cd /home/iuris/backend 
./vendor/bin/phinx migrate
chown -R www-data.www-data /home/iuris

/etc/init.d/php7.2-fpm start
exec /usr/sbin/nginx -c /etc/nginx/nginx.conf -g "daemon off;"





