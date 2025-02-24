# API-Symfony
## Installer les dépendances :
```
composer install
```

## Pour lancer le projet:

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
