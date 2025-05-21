# Utilise l'image officielle PHP avec Apache
FROM php:8.2-apache

# Active mod_rewrite (nécessaire pour Laravel)
RUN a2enmod rewrite

# Installe les extensions nécessaires à Laravel
RUN apt-get update && apt-get install -y \
    libzip-dev unzip zip git curl libpq-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copie la configuration Apache (LA LIGNE À AJOUTER ICI)
COPY ./docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copie ton code Laravel dans le conteneur
COPY . /var/www/html

COPY . /var/www/html
WORKDIR /var/www/html
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Donne les bonnes permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html


RUN docker-php-ext-install pdo pdo_pgsql

# Lancer Apache
CMD ["apache2-foreground"]