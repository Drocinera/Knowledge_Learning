# Utilise une image PHP avec Apache
FROM php:8.2-apache

# Installer extensions nécessaires à Symfony
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Activer mod_rewrite (important pour Symfony)
RUN a2enmod rewrite

# Copier le projet
COPY . /var/www/html

# Définir le dossier public comme racine
WORKDIR /var/www/html

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

# Permissions
RUN chown -R www-data:www-data /var/www/html/var

# Config Apache pour Symfony
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80