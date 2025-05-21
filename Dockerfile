# Utilise PHP 8.2 avec Apache
FROM php:8.2-apache

# Active mod_rewrite d’Apache
RUN a2enmod rewrite

# Installe les extensions nécessaires
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd

# Copie les fichiers du projet dans le conteneur
COPY . /var/www/html

# Place-toi dans le bon dossier
WORKDIR /var/www/html

# Copie composer depuis l’image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Installe les dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Donne les bons droits à Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache