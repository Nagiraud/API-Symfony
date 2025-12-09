FROM php:8.2-fpm-alpine
WORKDIR /
RUN  apk add --no-cache \
    bash \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    icu-dev \
    oniguruma-dev \
    nodejs npm
RUN docker-php-ext-install pdo pdo_mysql intl opcache zip
COPY composer.json composer.lock ./
RUN composer install
COPY . .
EXPOSE 8000
CMD ["symfony", "serve"]