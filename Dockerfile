# Étape 1 : Builder avec Composer
FROM php:8.2-fpm AS builder

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    git unzip libicu-dev libpq-dev libzip-dev zip \
    && docker-php-ext-install intl pdo pdo_pgsql zip opcache


# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copier les fichiers du projet
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Copier tout le reste du projet
COPY . .

# Étape 2 : Image finale
FROM php:8.2-fpm

# Installer dépendances système pour exécuter Symfony
RUN apt-get update && apt-get install -y \
    libicu-dev libpq-dev libzip-dev unzip \
    && docker-php-ext-install intl pdo pdo_pgsql zip opcache \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copier l’application depuis le builder
COPY --from=builder /app /app

# Config Render : Symfony écoute sur le port 10000
ENV PORT=10000
EXPOSE 10000

# Démarrage avec le serveur Symfony interne (suffisant pour Render)
CMD ["php", "-S", "0.0.0.0:10000", "-t", "public"]