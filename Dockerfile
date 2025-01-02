# Stage 1: PHP CLI with Composer for Laravel Setup
FROM php:8.2-cli as base

# Install dependencies
RUN apt update && apt install -y tzdata git unzip libpq-dev libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev libxpm-dev libgd-dev zlib1g-dev libzip-dev libmagickwand-dev && \
    docker-php-ext-install bcmath pdo pdo_pgsql gd zip exif

# Set timezone
ENV TZ="Asia/Jakarta"

# Set Composer environment variable
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Create Laravel project
RUN composer create-project laravel/laravel:^10.0 --remove-vcs -n /app

# Generate key and clear config
RUN php /app/artisan key:generate && php /app/artisan config:clear

# Copy necessary files
COPY .env.example .env
COPY composer.json composer.lock ./

# Install composer dependencies
RUN composer install

# Copy application files
COPY . .

# Stage 2: PHP with Apache
FROM php:apache-bookworm

# Install dependencies for Apache and PHP extensions
RUN apt update && apt install -y git unzip libpq-dev && \
    docker-php-ext-install bcmath pdo pdo_pgsql && \
    a2enmod rewrite headers

# Configure Apache to point to Laravel's public directory
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /app/public|' /etc/apache2/sites-enabled/000-default.conf
RUN sed -i 's|*:8080|*:2001|' /etc/apache2/sites-enabled/000-default.conf
RUN sed -i 's|Listen 8080|Listen 2001|' /etc/apache2/ports.conf
RUN sed -i 's|<Directory /var/www/>|<Directory /app/>|' /etc/apache2/apache2.conf
RUN sed -i 's|<Directory /var/www/>|<Directory /app/>|' /etc/apache2/conf-available/docker-php.conf

# Copy PHP configuration file
COPY php.ini-production /usr/local/etc/php/php.ini

# Copy application from the previous stage
COPY --chown=www-data:www-data --from=base /app/ /app/

# Expose the new port (2001)
EXPOSE 2001

# Start Apache and PHP-FPM
CMD ["apache2-foreground"]
