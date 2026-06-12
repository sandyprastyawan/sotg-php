FROM php:8.3-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

RUN sed -i 's|/var/www/html|/app/public|g' /etc/apache2/sites-available/000-default.conf

WORKDIR /app
COPY . /app

RUN chown -R www-data:www-data /app

EXPOSE 80

CMD bash -c "sed -i \"s/Listen 80/Listen $PORT/\" /etc/apache2/ports.conf && sed -i \"s/:80>/:$PORT>/\" /etc/apache2/sites-available/000-default.conf && exec apache2-foreground"