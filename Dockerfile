# Base stage: Build dependencies and Laravel setup
FROM php:8.2-fpm as base

# Install necessary PHP extensions and tools
RUN apt update && apt install -y \
    tzdata git unzip libpq-dev libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
    libxpm-dev libgd-dev zlib1g-dev libzip-dev libmagickwand-dev && \
    docker-php-ext-install bcmath pdo pdo_pgsql gd zip exif

# Set timezone
ENV TZ="Asia/Jakarta"

# Set Composer environment variable
ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set up application
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader -n

# Laravel setup
COPY . .
RUN php artisan key:generate && php artisan config:cache && php artisan route:cache && php artisan view:cache

# Fix permissions for Laravel storage and cache
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Production stage: Laravel + Nginx
FROM nginx:1.25 as production

# Install PHP-FPM
RUN apt update && apt install -y php8.2-fpm

# Copy Nginx configuration
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Copy the application from the base stage
COPY --from=base /app /app

# Expose Nginx port
EXPOSE 80

# Start PHP-FPM and Nginx
CMD service php8.2-fpm start && nginx -g "daemon off;"
