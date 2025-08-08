# Stage 1: Get the Composer binary
# This stage is for getting the Composer executable without installing it in the final image.
FROM composer:2 AS composer_stage

# Stage 2: Your application's base PHP image
# We use a specific version for stability.
FROM php:8.2-apache

# Install required PHP extensions and system dependencies.
# This single command installs:
#   - git & unzip (for Composer to work)
#   - libzip-dev (to compile the zip extension)
#   - pdo_mysql (to connect to a MySQL database)
# We chain the commands together to reduce the number of Docker layers and image size.
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip pdo_mysql

# Copy the Composer binary from the first stage.
# This ensures Composer is available to install dependencies.
COPY --from=composer_stage /usr/bin/composer /usr/local/bin/composer

# Set the working directory for all subsequent commands.
WORKDIR /var/www/html

# Copy ONLY the composer files first.
# This leverages Docker's build cache, so if your source code changes but dependencies don't,
# Docker doesn't need to reinstall Composer packages every time.
COPY composer.json composer.lock ./

# Install Composer dependencies.
# The --no-dev flag is crucial for a production environment to keep the image small.
RUN composer install --no-dev --optimize-autoloader

# Copy the rest of your application code.
COPY . .

# Set correct permissions for Laravel's writable directories.
# This is a critical step to prevent "permissions denied" errors.
RUN chown -R www-data:www-data /var/www/html/storage \
    && chown -R www-data:www-data /var/www/html/bootstrap/cache

# Fix the Apache ServerName warning.
# This is optional but cleans up your logs.
COPY apache-servername.conf /etc/apache2/conf-available/servername.conf
RUN a2enconf servername

# Expose port 80 and start the Apache server.
# This is the default command that runs when the container starts.
EXPOSE 80
CMD ["apache2-foreground"]
