# Stage 1: Get the Composer binary
FROM composer:2 AS composer_stage

# Stage 2: Your application's base image
FROM php:8.2-apache

# Copy the Composer binary from the first stage
COPY --from=composer_stage /usr/bin/composer /usr/local/bin/composer

# Set the working directory for your application
WORKDIR /var/www/html

# Copy your application files into the image
COPY . .

# Now, you can run the composer command successfully
RUN composer install --no-dev --optimize-autoloader

# Continue with the rest of your Dockerfile...
