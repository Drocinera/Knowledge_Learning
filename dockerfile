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

RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

RUN npm install && npm run build

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --optimize-autoloader

RUN php bin/console assets:install --env=prod

RUN php bin/console doctrine:migrations:migrate --no-interaction --env=prod

RUN php bin/console cache:clear --env=prod && \
    php bin/console cache:warmup --env=prod && \
    php bin/console debug:router --env=prod

# Apache → public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Permissions
RUN chown -R www-data:www-data /var/www/html/var

EXPOSE 80

