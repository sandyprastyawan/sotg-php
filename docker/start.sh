#!/bin/sh
sed -i "s/NGINX_PORT/${PORT:-8080}/" /etc/nginx/nginx.conf
php-fpm -D
nginx -g "daemon off;"