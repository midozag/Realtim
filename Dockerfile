# ─────────────────────────────────────────────
# Stage 1 — Node: build Vue/Vite assets
# ─────────────────────────────────────────────
FROM node:20 AS node-builder

WORKDIR /app

COPY package.json ./

RUN npm install --legacy-peer-deps

COPY resources/ resources/
COPY vite.config.js ./

RUN npm run build

# ─────────────────────────────────────────────
# Stage 2 — PHP final image
# ─────────────────────────────────────────────
FROM php:8.2-fpm-alpine

# Install system packages + autoconf for pecl
RUN apk add --no-cache \
    bash curl git \
    libpng-dev libjpeg-turbo-dev \
    oniguruma-dev libxml2-dev \
    zip unzip supervisor nginx \
    autoconf g++ make

RUN docker-php-ext-install \
    pdo_mysql mbstring exif \
    pcntl bcmath gd xml opcache

RUN pecl install redis && docker-php-ext-enable redis

# Remove build dependencies to keep image small
RUN apk del autoconf g++ make

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .

COPY --from=node-builder /app/public/build public/build

RUN composer dump-autoload --optimize

COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

EXPOSE 80 8080

ENTRYPOINT ["/entrypoint.sh"]