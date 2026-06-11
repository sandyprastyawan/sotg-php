FROM php:8.3-cli

RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /app
COPY . /app

EXPOSE 3000

CMD ["/bin/sh", "-c", "php -S 0.0.0.0:3000 -t /app/public"]