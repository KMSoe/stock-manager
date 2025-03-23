# Use an official PHP image with CLI
FROM php:8.3-cli

# Set the working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install pdo_mysql zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy the application files
COPY . .

# Install Composer dependencies
RUN composer install --no-dev --optimize-autoloader

# Expose port 8000 for the PHP development server
EXPOSE 8000

# Start the PHP development server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]