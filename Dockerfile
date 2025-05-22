FROM php:8.2-apache

# Installe les dépendances système et les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git unzip zip libpng-dev libonig-dev libxml2-dev libzip-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo_mysql mbstring zip gd

# Apache config (à créer dans le même dossier)
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

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

# Nettoyer & cacher config/routes/views
RUN php artisan config:clear
RUN php artisan route:clear
RUN php artisan view:clear
RUN php artisan config:cache
RUN php artisan route:cache

# Exécuter les migrations
##

# Clé Laravel
RUN php artisan key:generate


EXPOSE 80