FROM php:8.4-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy composer files first to leverage Docker cache
COPY laravel/composer.json laravel/composer.lock ./

# Install dependencies (no scripts yet, as code isn't copied)
RUN composer install --no-interaction --prefer-dist --no-scripts --no-autoloader

# Copy the rest of the application
COPY laravel/ .

# Finish composer installation (generate autoloader)
RUN composer dump-autoload --optimize

# Permissions (optional but good practice)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
