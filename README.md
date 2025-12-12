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
extension=pdo_pgsql
extension=pgsql
```

## Pour lancer le projet et le site par docker:

Créer le .env.local en dupliquant le .env, et décommentez cette ligne :
```
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

Lancer le projet:

```
docker compose up --build
```
