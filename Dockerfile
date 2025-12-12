FROM php:8.2-fpm-alpine
WORKDIR /app
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
    npm \
    postgresql-dev \
    postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql intl zip opcache
RUN docker-php-ext-install pdo pdo_mysql intl zip opcache
COPY --from=composer:2.9.2 /usr/bin/composer /usr/bin/composer
COPY . .
RUN composer install
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
RUN php bin/console asset-map:compile
EXPOSE 8000
ENTRYPOINT ["/entrypoint.sh"]

