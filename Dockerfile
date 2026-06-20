# syntax=docker/dockerfile:1

# ---------- Stage 1: build front-end assets with Vite ----------
FROM node:20-bookworm-slim AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# ---------- Stage 2: PHP runtime (php-fpm + nginx) ----------
FROM php:8.2-fpm-bookworm AS app

# System packages + PHP extensions required by the app:
#   pdo_mysql -> MySQL    gd/exif -> images & student photos    zip -> Excel export
#   bcmath -> math        opcache -> performance
RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx supervisor unzip git \
        libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
        libzip-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql gd zip bcmath exif opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer (from the official composer image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Install PHP dependencies first for better layer caching
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

# Copy application source (see .dockerignore — vendor/node_modules/.env excluded)
COPY . .

# Copy compiled front-end assets from the node stage
COPY --from=assets /app/public/build ./public/build

# Generate the optimized autoloader and run package discovery
RUN composer dump-autoload --optimize --no-dev \
    && chown -R www-data:www-data /var/www/html

# Server config
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN rm -f /etc/nginx/sites-enabled/default \
    && chmod +x /usr/local/bin/entrypoint.sh

# PHP runtime tuning + let Railway env vars reach php-fpm workers
RUN { \
      echo 'opcache.enable=1'; \
      echo 'opcache.enable_cli=0'; \
      echo 'opcache.memory_consumption=128'; \
      echo 'opcache.max_accelerated_files=20000'; \
      echo 'opcache.validate_timestamps=0'; \
    } > /usr/local/etc/php/conf.d/opcache.ini \
    && { \
      echo 'upload_max_filesize=20M'; \
      echo 'post_max_size=25M'; \
      echo 'memory_limit=512M'; \
    } > /usr/local/etc/php/conf.d/app.ini \
    && printf '[www]\nclear_env = no\n' > /usr/local/etc/php-fpm.d/zz-railway.conf

EXPOSE 8080
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
