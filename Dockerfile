# Start with a stable, official PHP image with an Apache web server
# Using a specific version like 8.2 ensures consistency
FROM heroku/php:8.2-apache

# Set the working directory inside the container to /app
# All subsequent commands will run from this directory
WORKDIR /app

# Install essential PHP extensions that Laravel needs
# pdo_mysql is for connecting to a MySQL database
RUN docker-php-ext-install pdo pdo_mysql

# --- Build Stage ---
# First, copy only the composer files. This is a Docker optimization technique.
# If these files don't change, Docker can use a cached layer, speeding up future builds.
COPY composer.json composer.lock ./

# Install Composer dependencies for production
# --no-dev: Skips development-only packages
# --optimize-autoloader: Creates a more efficient autoloader for better performance
RUN composer install --no-dev --optimize-autoloader

# Now, copy the rest of your application code into the container
COPY . .

# --- Final Configuration ---
# Set the correct file permissions for Laravel's storage and cache directories.
# This is a critical step that prevents "permission denied" errors.
# The web server (www-data) needs to be able to write to these folders.
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# This is the command that will run when the container starts.
# It caches Laravel's configuration for a speed boost and then starts the Apache server.
CMD php artisan config:cache && php artisan route:cache && vendor/bin/heroku-php-apache2 public/
