FROM php:8.3-cli

RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /app
COPY . /app

EXPOSE 8080

CMD ["/bin/sh", "-c", "php -S 0.0.0.0:8080 -t /app/public"]