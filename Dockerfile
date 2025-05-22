FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mbstring zip gd

COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Copie le code
COPY . /var/www/html
WORKDIR /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Supprime .env pour laisser Render injecter les variables
RUN rm -f .env

EXPOSE 80