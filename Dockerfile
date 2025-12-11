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
RUN curl -sS https://get.symfony.com/cli/installer | bash && \
    mv /root/.symfony*/bin/symfony /usr/local/bin/symfony
COPY . .
COPY --from=composer:2.9.2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction
ENV SYMFONY_SERVER_HOST=0.0.0.0
ENV SYMFONY_SERVER_PORT=8000
EXPOSE 8000
CMD ["symfony", "serve", "--no-tls", "--allow-http"]
