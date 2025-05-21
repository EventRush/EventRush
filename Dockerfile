FROM php:8.2-apache

# Installe les dépendances système et les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mbstring zip gd

# Active mod_rewrite pour Laravel
RUN a2enmod rewrite

# Copie les fichiers du projet
COPY . /var/www/html

# Accède au dossier du projet
WORKDIR /var/www/html

# Installe Composer (si pas déjà dans l'image de base)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installe les dépendances PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Donne les permissions correctes
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Supprime .env si présent (Render injectera les variables)
RUN rm -f .env

# Compile le cache de config (si APP_KEY est déjà défini dans Render)
RUN php artisan config:clear && php artisan config:cache

EXPOSE 80