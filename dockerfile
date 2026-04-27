FROM php:8.2-apache

# ---- Dépendances système ----
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev libfreetype6-dev libicu-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
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
    --optimize-autoloader \
    --no-scripts \
    --no-progress

# ---- Code source ----
COPY . .

# ---- Permissions ----
RUN mkdir -p var/cache var/log \
 && chown -R www-data:www-data /var/www/html \
 && chmod -R 775 var
RUN mkdir -p public/uploads/themes \
 && chown -R www-data:www-data public/uploads \
 && chmod -R 775 public/uploads

# ---- Apache DocumentRoot ----
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# ---- Env ----
ENV APP_ENV=prod
ENV APP_DEBUG=0

# ---- Build assets ----
RUN php bin/console asset-map:compile

# ---- Script de démarrage ----
CMD ["sh", "-c", "\
echo 'Listening on port ${PORT}' && \
echo \"Listen ${PORT}\" > /etc/apache2/ports.conf && \
sed -i \"s/:80/:${PORT}/g\" /etc/apache2/sites-available/000-default.conf && \
chown -R www-data:www-data var; \
chmod -R 775 var; \
php bin/console doctrine:database:create --if-not-exists && \
php bin/console doctrine:migrations:migrate --no-interaction && \
php bin/console doctrine:fixtures:load --no-interaction && \
apache2-foreground"]

EXPOSE 10000