FROM php:8.3-fpm-alpine

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apk add --no-cache nginx supervisor

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

WORKDIR /app
COPY . /app
RUN chown -R www-data:www-data /app

EXPOSE 8080

CMD ["/start.sh"]