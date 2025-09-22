FROM php:7.4-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip unzip git curl && \
    docker-php-ext-install mysqli pdo_mysql mbstring exif pcntl bcmath gd

# Copy custom PHP config
COPY ./docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html
