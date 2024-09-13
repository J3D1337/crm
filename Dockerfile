# Use an official PHP image with Apache
FROM php:8.1-apache

# Enable mod_rewrite for clean URLs
RUN a2enmod rewrite

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy application files to the container
COPY public/ /var/www/html/

# Install any required PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80 to allow web traffic
EXPOSE 80
