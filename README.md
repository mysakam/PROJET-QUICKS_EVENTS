# QuickEvents

Application PHP MVC separant un front client et un back-office administrateur.

## Vue d'ensemble

- Front : catalogue, authentification client, panier, parcours devis, compte client, dashboard client.
- Back : connexion administrateur, gestion des categories, prestations, clients, devis, factures, medias et statistiques.
- Shared : composants communs, en particulier le mailer et la validation partagee.

## Structure utile

```text
PROJET-QUICKS_EVENTS/
|- front/
|  |- controllers/
|  |- models/
|  |- public/
|  |- views/
|- back/
|  |- controllers/
|  |- database/
|  |- public/
|  |- views/
|- docker/
|- shared/
|- tests/
|- tools/
|- docker-compose.yml
|- docker-compose.prod.yml
|- composer.json
```

## Base de donnees reelle

Le socle SQL principal est defini dans [back/database/schema.sql](back/database/schema.sql).

Tables initialisees par le schema :

- clients
- categories
- prestataires
- prestations
- event_medias
- devis
- devis_lignes
- factures

Tables creees automatiquement par certains modeles si elles sont absentes :

- notifications : creee par [front/models/NotificationModel.php](front/models/NotificationModel.php) pour les notifications admin.
- prestataire_disponibilites : creee par [front/models/PrestataireDisponibiliteModel.php](front/models/PrestataireDisponibiliteModel.php) pour le calendrier de disponibilite des prestataires.

Notes importantes :

- [front/models/FactureModel.php](front/models/FactureModel.php) contient aussi une securite de creation ou de migration legere sur la table factures si elle n'est pas encore presente ou si certains index ou champs manquent.
- En environnement Docker local, la base est initialisee depuis [back/database/schema.sql](back/database/schema.sql) et [back/database/seeds.sql](back/database/seeds.sql) au premier demarrage du conteneur MySQL.
- En production Docker, seul le schema est monte automatiquement. Les seeds ne doivent pas etre charges en production publique.

## Authentification

- Front client : [front/controllers/AuthController.php](front/controllers/AuthController.php)
- Dashboard client : [front/controllers/DashboardController.php](front/controllers/DashboardController.php)
- Connexion admin back-office : [back/controllers/DashBoard.php](back/controllers/DashBoard.php)

## Docker

Documentation disponible :

- Vue rapide Docker : [docker/README.md](docker/README.md)
- Procedure production : [docker/PRODUCTION_DEPLOYMENT.md](docker/PRODUCTION_DEPLOYMENT.md)
- Guide pas a pas : [docker/DEPLOYMENT_STEPS.md](docker/DEPLOYMENT_STEPS.md)

## Commandes utiles

Execution locale avec Docker :

```bash
docker compose up -d --build
```

Arret :

```bash
docker compose down
```

Tests PHP :

```bash
composer test
```

Bootstrap local :

```bash
composer bootstrap-local
```

Execution de la migration incrementale sur une base existante :

```bash
mysql -u root -p quickevents < back/database/migration_2026_07_20_runtime_tables.sql
```

Avec Docker local :

```bash
docker exec -i quickevents-db mysql -uroot -pVOTRE_MOT_DE_PASSE quickevents < back/database/migration_2026_07_20_runtime_tables.sql
```

## Points d'attention

- Le front et le back utilisent la meme base MySQL.
- Certaines tables complementaires peuvent apparaitre apres utilisation fonctionnelle de l'application, meme si elles ne sont pas encore dans le schema principal.
- Si vous recreez la base, pensez a maintenir coherents le schema SQL, les seeds et la documentation.
