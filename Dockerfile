FROM php:8.2-cli as base


RUN apt update && apt install -y \
    tzdata git unzip libpq-dev libpng-dev libjpeg-dev libwebp-dev libfreetype6-dev \
    libxpm-dev libgd-dev zlib1g-dev libzip-dev libmagickwand-dev && \
    docker-php-ext-install bcmath pdo pdo_pgsql gd zip exif


ENV TZ="Asia/Jakarta"


ENV COMPOSER_ALLOW_SUPERUSER=1
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set up application
COPY php.ini-development /usr/local/etc/php/conf.d/php.ini
WORKDIR /app
RUN composer create-project laravel/laravel:^10.0 --remove-vcs -n /app
RUN php /app/artisan key:generate && php /app/artisan config:clear


COPY .env.example .env
COPY composer* .
RUN composer install


COPY . .

FROM php:apache-bookworm


RUN apt update && apt install -y git unzip libpq-dev && \
    docker-php-ext-install bcmath pdo pdo_pgsql && \
    a2enmod rewrite headers


RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /app/public|' /etc/apache2/sites-enabled/000-default.conf
RUN sed -i 's|*:80|*:8080|' /etc/apache2/sites-enabled/000-default.conf
RUN sed -i 's|Listen 80|Listen 8080|' /etc/apache2/ports.conf
RUN sed -i 's|<Directory /var/www/>|<Directory /app/>|' /etc/apache2/apache2.conf
RUN sed -i 's|<Directory /var/www/>|<Directory /app/>|' /etc/apache2/conf-available/docker-php.conf
COPY php.ini-production /usr/local/etc/php/php.ini


COPY --chown=www-data:www-data --from=base /app/ /app/


EXPOSE 8080
