#!/bin/sh
echo "PORT is: $PORT"
sed -i "s/NGINX_PORT/${PORT:-8080}/g" /etc/nginx/nginx.conf
echo "Starting supervisord..."
exec supervisord -c /etc/supervisord.conf