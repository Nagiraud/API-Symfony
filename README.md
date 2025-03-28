# API-Symfony
## Le projet :
Ce projet est une API Symfony permettant de gérer une collection d'artistes et d'événements musicaux.
Vous pouvez ajouter, modifier et supprimer des artistes ainsi que des événements.
## Cloner le projet :
Placez vous dans le dossier ou vous souhaitez mettre ce site
```
git clone https://github.com/Nagiraud/API-Symfony.git
```
ouvrez le dossier

## Installer les dépendances :
```
composer install
```
## Activer les extensions:
Allez dans votre php.ini, et vérifié d'avoir décommentez ces lignes :
```
extension=pdo_sqlite
extension=fileinfo
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
## Crédit:
créer par [Nathan Giraud]([https](https://github.com/Nagiraud), [Julien Demaiziére](https://github.com/julienlink) et [Elyo Maamari](https://github.com/Elyo17)

