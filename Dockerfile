FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-scripts --no-autoloader

# Copy package files
COPY package*.json ./

# Install Node dependencies with clean install
RUN npm ci --legacy-peer-deps

# Copy application code
COPY . .

# Generate autoloader
RUN composer dump-autoload --optimize

# Clean npm cache and reinstall if needed
RUN rm -rf node_modules package-lock.json && \
    npm install --legacy-peer-deps

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Create .env file from example if it doesn't exist


EXPOSE 9000

CMD ["php-fpm"]