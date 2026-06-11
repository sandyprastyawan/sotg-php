FROM php:8.3-cli

RUN docker-php-ext-install mysqli pdo pdo_mysql

WORKDIR /app
COPY . /app

RUN chmod +x /app/start.sh

EXPOSE 8080

CMD ["/bin/sh", "/app/start.sh"]