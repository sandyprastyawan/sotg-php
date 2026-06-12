FROM php:8.3-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Hapus semua MPM symlink, buat ulang hanya prefork
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load \
          /etc/apache2/mods-enabled/mpm_*.conf && \
    ln -s /etc/apache2/mods-available/mpm_prefork.load /etc/apache2/mods-enabled/mpm_prefork.load && \
    ln -s /etc/apache2/mods-available/mpm_prefork.conf /etc/apache2/mods-enabled/mpm_prefork.conf

RUN a2enmod rewrite

RUN sed -i 's|/var/www/html|/app/public|g' /etc/apache2/sites-available/000-default.conf
RUN sed -i 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost *:8080>/g' /etc/apache2/sites-available/000-default.conf

WORKDIR /app
COPY . /app

RUN chown -R www-data:www-data /app

EXPOSE 8080

CMD ["apache2-foreground"]