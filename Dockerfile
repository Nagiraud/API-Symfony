FROM php:8.2-fpm-alpine
WORKDIR /
RUN apk add --no-cache \
    git \
    unzip \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    zip \
    curl \
    bash \
    nodejs \
    npm
RUN docker-php-ext-install pdo pdo_mysql intl zip opcache
COPY --from=composer:2.9.2 /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install --no-interaction
RUN php bin/console asset-map:compile
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

