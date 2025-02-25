# API-Symfony
## Installer les dépendances :
```
composer install
```

## Pour lancer le projet:

Créer le .env.local en dupliquant le .env, et décommentez cette ligne :
```
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

Créer la base:

```
symfony console doctrine:database:create
```

La première migration crééra les tables nécessaires à l'application:
```
symfony console make:migration
```

Puis appliquons la migration :
```
symfony console doctrine:migrations:migrate
```
