FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev libicu-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Cache layer
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-scripts \
    --no-progress

# Copy project
COPY . .

RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/var


ENV APP_ENV=prod
ENV APP_DEBUG=0

# Apache config
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

CMD ["sh", "-c", " until php -r 'try { new PDO(\"pgsql:host=$DATABASE_HOST;dbname=$DATABASE_NAME\", \"$DATABASE_USER\", \"$DATABASE_PASSWORD\"); echo \"DB OK\"; } catch (Exception $e) { exit(1); }'; do   echo 'Waiting for database...';  sleep 2; done; php bin/console doctrine:migrations:migrate --no-interaction && php bin/console doctrine:fixtures:load --no-interaction && apache2-foreground "]
EXPOSE 80