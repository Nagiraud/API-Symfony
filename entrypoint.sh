#!/bin/sh
set -e

echo "⏳ Attente de la base de données..."
export PGPASSWORD="${POSTGRES_PASSWORD:-!ChangeMe!}"
until pg_isready -h database -p 5432 -U app; do
  sleep 1
done
echo "✅ Base de données prête."

echo "Création de la base..."
php bin/console doctrine:database:create --if-not-exists
echo "Création des migrations..."
php bin/console make:migration
echo "Exécution des migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

echo "🚀 Lancement du serveur PHP"
exec php -S 0.0.0.0:8000 -t public
