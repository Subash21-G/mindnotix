# Stage 1: Get the Composer binary
FROM composer:2 AS composer_stage

# Stage 2: Your application's base PHP image
FROM php:8.2-apache

# Copy the Composer binary from the first stage
COPY --from=composer_stage /usr/bin/composer /usr/local/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy ONLY the composer files first to leverage Docker's build cache
COPY composer.json composer.lock ./

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of your application code
COPY . .

# Set correct permissions for Laravel's writable directories
RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache

# Continue with the rest of your Dockerfile...
