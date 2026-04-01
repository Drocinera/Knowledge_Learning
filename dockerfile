FROM php:8.2-apache

# Dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd

# Apache
RUN a2enmod rewrite

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Projet
WORKDIR /var/www/html
COPY . .
COPY .env.local.php .env.local.php

# Variables d'environnement
ENV APP_ENV=prod
ENV APP_DEBUG=0

# Installer dépendances (SANS scripts)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Lancer Symfony proprement
RUN php bin/console cache:clear --env=prod
RUN php bin/console cache:warmup --env=prod

# Apache → public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html/var

EXPOSE 80