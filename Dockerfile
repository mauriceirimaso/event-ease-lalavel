FROM php:8.2-apache

# 1. Install system dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    libonig-dev && \
    docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    zip

# 2. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Configure Apache
RUN a2enmod rewrite && \
    echo "DocumentRoot /var/www/html/public" > /etc/apache2/sites-available/000-default.conf

# 4. Set up app with proper permissions
WORKDIR /var/www/html
COPY . .

# Fix permissions before composer install
RUN mkdir -p storage/framework/{sessions,views,cache} && \
    chown -R www-data:www-data storage bootstrap/cache && \
    chmod -R 775 storage bootstrap/cache

RUN composer install --no-dev --optimize-autoloader