# Stage 1: Get the Composer binary
FROM composer:2 AS composer_stage

# Stage 2: Your application's base PHP image
FROM php:8.2-apache

# Install required PHP extensions and system dependencies
# This step fixes the "git not found" and "zip extension missing" errors
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql

# Copy the Composer binary from the first stage
# This step fixes the "composer not found" error
COPY --from=composer_stage /usr/bin/composer /usr/local/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy ONLY the composer files first to leverage Docker's build cache
COPY composer.json composer.lock ./

# Install Composer dependencies
# This step will now succeed because git, unzip, and the zip extension are installed
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of your application code
COPY . .

# Set correct permissions for Laravel's writable directories
# This is crucial for a Laravel app to function
RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache

COPY apache-servername.conf /etc/apache2/conf-available/servername.conf
RUN a2enconf servername

# Expose port 80 and start the Apache server
EXPOSE 80
CMD ["apache2-foreground"]
