# -------------------------------
# Stage 1: Build PHP dependencies & Vite assets
# -------------------------------
FROM composer:2 AS build

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

COPY . .

# Build frontend assets
RUN npm install
RUN npm run build || true

# -------------------------------
# Stage 2: Laravel Production Image (PHP 8.2 + FPM)
# -------------------------------
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip libpng-dev libonig-dev libxml2-dev zlib1g-dev libzip-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

WORKDIR /var/www

# Copy built vendors and assets from builder
COPY --from=build /app /var/www

# Set permissions
RUN chmod -R 777 storage bootstrap/cache

# Storage link
RUN php artisan storage:link || true

# Generate app key if missing
RUN php artisan key:generate --force || true

# Expose port
EXPOSE 8000

# Run Laravel server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
