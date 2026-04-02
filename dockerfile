FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

ENV APP_RUNTIME_ENV=prod
ENV SYMFONY_DOTENV_VARS=0
ENV APP_ENV=prod
ENV APP_DEBUG=0

RUN composer install --no-dev --optimize-autoloader

RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

RUN mkdir -p var/cache var/log && chown -R www-data:www-data var

EXPOSE 80