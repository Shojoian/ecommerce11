# ───────────────────────────────
# 1. Base PHP image
# ───────────────────────────────
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo pdo_mysql

# ───────────────────────────────
# 2. Set working directory
# ───────────────────────────────
WORKDIR /app

# Copy entire project
COPY . .

# ───────────────────────────────
# 3. Install Composer dependencies
# ───────────────────────────────
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN mkdir -p /app/storage/framework/{sessions,views,cache} \
    && chmod -R 775 /app/storage /app/bootstrap/cache


# ───────────────────────────────
# 4. Install NPM + Vite dependencies
# ───────────────────────────────
RUN npm install
RUN chmod +x node_modules/.bin/vite
RUN npm run build

# ───────────────────────────────
# 5. Laravel permissions
# ───────────────────────────────
RUN chmod -R 777 storage bootstrap/cache

# ───────────────────────────────
# 6. Serve Laravel with PHP-FPM
# Railway will run `php artisan serve` unless start command is set
# ───────────────────────────────
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

