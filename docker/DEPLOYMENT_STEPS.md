# Etapes de deploiement Docker

Ce document sert de procedure operationnelle pour lancer QuickEvents avec Docker, en local comme en production.

## 1. Prerequis

- Docker installe
- Docker Compose disponible
- Git installe
- Ports libres : 8080, 8081 et 3307 en local
- Ports 80 et 443 ouverts en production

## 2. Verifier les fichiers utiles

Avant tout deploiement, verifier la presence de :

- [docker-compose.yml](../docker-compose.yml)
- [docker-compose.prod.yml](../docker-compose.prod.yml)
- [back/database/schema.sql](../back/database/schema.sql)
- [back/database/seeds.sql](../back/database/seeds.sql)
- [tools/deploy_prod.sh](../tools/deploy_prod.sh)

## 3. Deploiement Docker local

### 3.1 Preparer l'environnement

1. Copier `.env.docker.example` vers `.env`.
2. Adapter au besoin les variables de ports et de base.

Variables attendues :

- `FRONT_PORT`
- `BACK_PORT`
- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `DB_CHARSET`
- `MYSQL_DATABASE`
- `MYSQL_USER`
- `MYSQL_PASSWORD`
- `MYSQL_ROOT_PASSWORD`

### 3.2 Construire et lancer

```bash
docker compose up -d --build
```

### 3.3 Verifier l'etat

```bash
docker compose ps
docker compose logs -f
```

### 3.4 Acces attendus

- Front : http://localhost:8080
- Back : http://localhost:8081/admin-login
- MySQL : localhost:3307

### 3.5 Initialisation de la base

Au premier demarrage du conteneur MySQL :

1. le schema est charge depuis [back/database/schema.sql](../back/database/schema.sql)
2. les donnees de demo sont chargees depuis [back/database/seeds.sql](../back/database/seeds.sql)

Attention : si le volume Docker MySQL existe deja, ces scripts d'initialisation ne sont pas rejoues automatiquement.

### 3.6 Repartir d'une base vide

```bash
docker compose down -v
docker compose up -d --build
```

Cette commande supprime aussi le volume MySQL local.

### 3.7 Appliquer la migration incrementale sur une base existante

Si votre base existe deja et que vous ne voulez pas la recreer, utilisez la migration suivante :

- [back/database/migration_2026_07_20_runtime_tables.sql](../back/database/migration_2026_07_20_runtime_tables.sql)

Commande locale hors conteneur :

```bash
mysql -u root -p quickevents < back/database/migration_2026_07_20_runtime_tables.sql
```

Commande si MySQL tourne dans Docker :

```bash
docker exec -i quickevents-db mysql -uroot -pVOTRE_MOT_DE_PASSE quickevents < back/database/migration_2026_07_20_runtime_tables.sql
```

Effets attendus :

1. creation de `notifications` si absente
2. creation de `prestataire_disponibilites` si absente
3. ajout de `factures.date_envoi_mail` si absent
4. ajout de l'unicite sur `factures.id_devis` si aucun doublon n'existe

## 4. Tables a connaitre en exploitation

Tables du schema principal :

- clients
- categories
- prestataires
- prestations
- event_medias
- devis
- devis_lignes
- factures

Tables creees automatiquement par l'application si elles n'existent pas encore :

- notifications
- prestataire_disponibilites

## 5. Deploiement Docker en production

### 5.1 Preparer le serveur

1. Installer Docker et Docker Compose.
2. Cloner le depot.
3. Ouvrir les ports 80 et 443.
4. Configurer les DNS de `APP_DOMAIN` et `ADMIN_DOMAIN` vers le serveur.

### 5.2 Preparer l'environnement production

1. Copier `.env.production.example` vers `.env.production`.
2. Renseigner les domaines, mots de passe et secrets.

Variables critiques :

- `APP_DOMAIN`
- `ADMIN_DOMAIN`
- `ACME_EMAIL`
- `MYSQL_DATABASE`
- `MYSQL_USER`
- `MYSQL_PASSWORD`
- `MYSQL_ROOT_PASSWORD`
- `DB_HOST`
- `DB_PORT`
- `DB_NAME`
- `DB_USER`
- `DB_PASS`
- `DB_CHARSET`
- `APP_ENV`
- `APP_DEBUG`

### 5.3 Lancer le deploiement

Commande recommandee :

```bash
bash tools/deploy_prod.sh
```

Commande equivalente :

```bash
docker compose -f docker-compose.prod.yml --env-file .env.production up -d --build
```

### 5.4 Verifier apres deploiement

```bash
docker compose -f docker-compose.prod.yml --env-file .env.production ps
docker compose -f docker-compose.prod.yml --env-file .env.production logs -f
```

### 5.5 HTTPS et Traefik

La production utilise Traefik et Let's Encrypt.

Verifier que :

1. `APP_DOMAIN` repond bien publiquement.
2. `ADMIN_DOMAIN` repond bien publiquement.
3. `docker/traefik/acme.json` est accessible en ecriture par le script de deploiement.

## 6. Mise a jour d'une instance existante

```bash
git pull
docker compose up -d --build
```

En production :

```bash
git pull
bash tools/deploy_prod.sh
```

## 7. Sauvegarde et restauration

### 7.1 Sauvegarder la base

```bash
docker exec quickevents-db mysqldump -uroot -pROOT_PASSWORD quickevents > quickevents.sql
```

Adapter `ROOT_PASSWORD` avec la vraie valeur.

### 7.2 Restaurer la base

```bash
cat quickevents.sql | docker exec -i quickevents-db mysql -uroot -pROOT_PASSWORD quickevents
```

## 8. Problemes frequents

### Les donnees seed ne se rechargent pas

Cause : le volume MySQL existe deja.

Correction :

```bash
docker compose down -v
docker compose up -d --build
```

### Une table existe en runtime mais pas dans le schema

Cause : certains modeles creent leurs propres tables si elles manquent.

Correction : appliquer la migration incrementale si disponible, puis reporter la table dans le schema principal et dans la documentation si elle devient structurelle.

### Le front ou le back ne demarre pas

Verifier :

1. les variables `.env`
2. les logs Docker
3. la disponibilite du conteneur MySQL
4. les ports exposes deja utilises par une autre application

## 9. Check-list de fin

1. Le front repond.
2. Le back repond sur la page admin-login.
3. La base MySQL est joignable.
4. Les conteneurs sont healthy.
5. Les logs ne montrent pas d'erreur fatale PHP ou MySQL.
