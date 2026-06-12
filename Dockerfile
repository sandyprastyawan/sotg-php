FROM php:8.3-fpm-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apk add --no-cache nginx supervisor

# Copy configs
COPY docker/nginx.conf /etc/nginx/nginx.conf.template
COPY docker/supervisord.conf /etc/supervisord.conf

WORKDIR /app
COPY . /app
RUN chown -R www-data:www-data /app

EXPOSE 8080

# Inline CMD — tidak butuh start.sh sama sekali
CMD ["/bin/sh", "-c", "sed s/NGINX_PORT/${PORT:-8080}/g /etc/nginx/nginx.conf.template > /etc/nginx/nginx.conf && exec supervisord -c /etc/supervisord.conf"]