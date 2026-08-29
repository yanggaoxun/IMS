# syntax=docker/dockerfile:1

# ---- 前端资源构建 ----
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

# ---- PHP 依赖 ----
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --optimize-autoloader --ignore-platform-reqs

# ---- 运行时：FrankenPHP + Octane ----
FROM dunglas/frankenphp:php8.4-alpine

RUN install-php-extensions pdo_mysql pdo_sqlite pcntl

WORKDIR /app

COPY . .
COPY --from=vendor /app/vendor ./vendor
COPY --from=assets /app/public/build ./public/build

RUN cp .env.example .env \
    && mkdir -p storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

ENV OCTANE_SERVER=frankenphp \
    SERVER_NAME=:80

EXPOSE 80

# 容器启动时运行迁移（幂等），再启动 Octane
CMD ["sh", "-c", "php artisan migrate --force || true; php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=80 --admin-port=2019"]
