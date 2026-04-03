FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy only composer files first (cache Docker)
COPY composer.json composer.lock ./

# Install PHP dependencies (prod)
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-scripts \
    --no-progress

# Copy project files
COPY . .

# Set environment variables
ENV APP_ENV=prod
ENV APP_DEBUG=0

# Fix Apache document root
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Create required directories
RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var

RUN chown -R www-data:www-data /var/www/html

RUN php bin/console doctrine:migrations:migrate --no-interaction || true

# Clear cache (IMPORTANT après copy)
RUN php bin/console cache:clear --no-warmup

EXPOSE 80