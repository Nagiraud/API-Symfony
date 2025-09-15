FROM ubuntu:latest
LABEL authors="natha"

# Installer dépendances système
RUN apt-get update && apt-get install -y
RUN docker-php-ext-install intl pdo pdo_pgsql zip opcache

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier projet
WORKDIR /app
COPY . .

# Installer les dépendances
RUN composer install --no-dev --optimize-autoloader

# Config cache & permissions
RUN mkdir -p var/cache var/log && chmod -R 777 var

# Commande de lancement (avec Symfony CLI ou PHP built-in server)
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]