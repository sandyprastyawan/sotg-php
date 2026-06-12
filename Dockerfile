FROM php:8.3-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN a2enmod rewrite

# Fix: disable MPM event, enable MPM prefork
RUN a2dismod mpm_event && a2enmod mpm_prefork

RUN sed -i 's|/var/www/html|/app/public|g' /etc/apache2/sites-available/000-default.conf

RUN sed -i 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/g' /etc/apache2/sites-available/000-default.conf

WORKDIR /app
COPY . /app

RUN chown -R www-data:www-data /app

EXPOSE 8080

CMD ["apache2-foreground"]