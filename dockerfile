FROM php:8.2-apache

# ---- Dépendances système ----
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev libicu-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd intl opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

    
# ---- Apache ----
RUN a2enmod rewrite
RUN echo '<Directory /var/www/html/public>' >> /etc/apache2/apache2.conf \
 && echo '    AllowOverride All' >> /etc/apache2/apache2.conf \
 && echo '</Directory>' >> /etc/apache2/apache2.conf \
 && echo "ServerName localhost" >> /etc/apache2/apache2.conf

# ---- PHP prod config ----
RUN echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
 && echo "opcache.validate_timestamps=0" >> /usr/local/etc/php/conf.d/opcache.ini

# ---- Composer ----
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ---- Cache Docker optimisé ----
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-scripts \
    --no-progress

# ---- Code source ----
COPY . .

# ---- Permissions ----
RUN mkdir -p var/cache var/log \
 && chown -R www-data:www-data /var/www/html \
 && chmod -R 775 var

# ---- Apache DocumentRoot ----
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# ---- Env ----
ENV APP_ENV=prod
ENV APP_DEBUG=0

# ---- Build assets ----
RUN php bin/console asset-map:compile

# ---- Script de démarrage ----
CMD ["sh", "-c", "\
echo 'Waiting for database...'; \
until php bin/console dbal:run-sql \"SELECT 1\"; do sleep 2; done; \
echo 'Database ready!'; \
chown -R www-data:www-data var; \
chmod -R 775 var; \
php bin/console doctrine:migrations:migrate --no-interaction || true; \
php bin/console app:create-admin || true; \ 
php bin/console debug:router;\
apache2-foreground"]