mis en place de l'environnement




## 1. Principe

Le projet est séparé en deux zones :

- **Front** : espace public, client et prestataire ;
- **Back** : espace d’administration.

Cette séparation facilite la maintenance, la sécurité et la lecture du projet.

---

## 2. Racine du projet

```text
QUICK-EVENTS/
├── front/
├── back/
├── shared/
├── database/
├── uploads/
└── README.md
```

---

## 3. Partie Front

La partie front gère l’accueil, l’authentification, la navigation client, le catalogue, le panier et les demandes de devis.

front/
├── public/
│   ├── index.php
│   └── .htaccess
├── assets/
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── main.js
│   └── images/
├── config/
│   ├── app.php
│   └── database.php
├── core/
│   ├── Controller.php
│   ├── Router.php
│   ├── Database.php
│   ├── Auth.php
│   └── Session.php
├── controllers/
│   ├── HomeController.php
│   ├── AuthController.php
│   ├── CatalogueController.php
|   |── EventControler.php
│   ├── PanierController.php
│   ├── DevisController.php
│   ├── ClientController.php
│   └── PrestataireController.php
├── models/
│   ├── User.php
│   ├── Prestation.php
│   ├── Panier.php
│   └── DemandeDevis.php
│   ├── Devis.php
│   ├── Event.php
│   └── Package.php


├── middleware/
│   ├── AuthMiddleware.php
│   └── GuestMiddleware.php
├── routes/
│   └── web.php
└── views/
    ├── event/
    │   ├── create.php
    ├── home/
    │   └── index.php
    ├── auth/
    │   ├── login.php
    │   ├── register.php
    ├── catalogue/
    │   ├── index.php
    │   └── packages.php
    |   ├── prestations.php
    ├── panier/
    │   └── index.php
    ├── devis/
    │   ├── create.php
    │   └── show.php
    ├── client/
    │   └── dashboard.php
    └── prestataire/
        └── dashboard.php


## 4. Partie Back

La partie back gère l’administration générale, les utilisateurs, les prestataires, les prestations, les catégories et les devis.
back/
├── public/
│   ├── index.php
│   └── .htaccess
├── assets/
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
├── config/
│   ├── app.php
│   └── database.php
├── core/
│   ├── Controller.php
│   ├── Router.php
│   ├── Database.php
│   ├── Auth.php
│   └── Session.php
├── controllers/
│   ├── DashboardController.php
│   ├── UsersController.php
│   ├── PrestationsController.php
│   ├── CategoriesController.php
│   └── DevisController.php
├── models/
│   ├── User.php
│   ├── Prestation.php
│   ├── Categorie.php
│   ├── DemandeDevis.php
│   └── Devis.php
|   └── Media.php
├── middleware/
│   ├── AuthMiddleware.php
│   └── AdminMiddleware.php
├── routes/
│   └── web.php
└── views/
    ├── auth/
    │   └── login.php
    ├── dashboard/
    │   └── index.php
    ├── users/
    │   ├── index.php
    ├── prestations/
    │   ├── index.php
    ├── categories/
    │   ├── index.php
    └── devis/
    |   ├── index.php
        └── show.php
    ├── media/
│       ├── index.php
│       ├── create.php
│       ├── edit.php    

---

## 5. Partie partagée

Le dossier `shared/` contient les éléments communs aux deux environnements pour éviter les doublons.

shared/
├── helpers/
│   ├── url.php
│   └── view.php
├── validation/
│   └── rules.php
└── mail/
    └── mailer.php

---

## 6. Base de données et fichiers communs

uploads/
├── users/
├── prestataires/
└── prestations/

database/
├── schema.sql
└── seeds.sql

---

## 7. Recommandation d’usage

- **Front** pour tout ce que voit et utilise l’utilisateur final.
- **Back** pour l’administration et la validation métier.
- **Shared** pour les fonctions, et validations communs.

Cette séparation rend le projet plus lisible et limite les collisions entre l’interface publique et l’interface admin.


## 8. Rôle de chaque contrôleur

HomeController.php : affiche la home page.

AuthController.php : gère inscription, connexion, déconnexion.

CatalogueController.php : affiche les packages et les prestations individuelles.

EventController.php : gère le formulaire “Créer mon événement”.

PanierController.php : gère les choix ajoutés au panier.

DevisController.php : transforme le panier et l’événement en demande de devis globale.

ClientController.php : gère l’espace client.

PrestataireController.php : gère l’espace prestataire minimal.


## 9. Rôle des vues front

home/index.php : accueil.

auth/login.php : connexion.

auth/register.php : inscription.

catalogue/index.php : point d’entrée catalogue.

catalogue/packages.php : affichage des packages.

catalogue/prestations.php : affichage des prestations individuelles.

event/create.php : formulaire “Créer mon événement”.

panier/index.php : panier utilisateur.

devis/create.php : récapitulatif avant envoi du devis.

devis/show.php : affichage du devis global demandé.

client/dashboard.php : tableau de bord client.

prestataire/dashboard.php : tableau de bord prestataire.


## 10. Rôle de chaque contrôleur back

DashboardController.php : vue d’ensemble admin.

UsersController.php : gestion des utilisateurs.

PrestationsController.php : gestion des contenus de catalogue.

CategoriesController.php : gestion des catégories.

DevisController.php : gestion des demandes et devis.

MediaController.php : publication, suppression et gestion des images/vidéos.


## 11. Rôle des vues back

auth/login.php : connexion admin.

dashboard/index.php : synthèse admin.

users/index.php : liste utilisateurs.

prestations/index.php : catalogue admin.

categories/index.php : liste catégories.

devis/index.php : liste des demandes/devis.

devis/show.php : détail d’un devis global.

media/index.php : liste médias.

media/create.php : ajout image/vidéo.

media/edit.php : modification ou suppression média.

# Modélisation MERISE définitive — QUICK'EVENTS (version complète)

## 1. Objectif métier

Le système permet à un utilisateur de :

1. consulter la home page ;
2. s'inscrire ou se connecter ;
3. parcourir les catalogues de packages et de prestations individuelles ;
4. créer un événement via un formulaire dédié ;
5. ajouter des prestations à un panier ;
6. demander un devis global fondé sur tous ses choix ;
7. suivre l'état de sa demande et de son devis.

Le système permet aussi à l'admin de :

1. publier des images et vidéos ;
2. supprimer des médias ;
3. gérer les contenus des prestataires ;
4. suivre les demandes et les devis.

---

## 2. MCD — Modèle Conceptuel de Données

### 2.1 Entités

#### UTILISATEUR
- id_utilisateur
- role_utilisateur
- prenom
- nom
- email
- telephone
- mot_de_passe_hash
- actif
- date_creation
- date_modification

#### PRESTATAIRE
- id_prestataire
- id_utilisateur
- nom_commercial
- raison_sociale
- description
- adresse
- ville
- pays
- site_web
- email_contact
- telephone_contact
- statut_validation
- date_creation
- date_modification

#### CATEGORIE_PRESTATION
- id_categorie
- nom_categorie
- description
- actif
- ordre_affichage

#### PRESTATION
- id_prestation
- id_prestataire
- id_categorie
- titre_prestation
- description_prestation
- prix_unitaire
- unite_prix
- prix_minimum
- conditions_tarifaires
- delai_reservation
- actif
- date_creation
- date_modification

#### MEDIA
- id_media
- id_prestation
- type_media
- url_media
- titre_media
- description_media
- ordre_affichage
- principal
- date_creation

#### PANIER
- id_panier
- id_utilisateur
- statut_panier
- date_creation
- date_modification

#### LIGNE_PANIER
- id_ligne_panier
- id_panier
- id_prestation
- quantite
- prix_snapshot
- titre_snapshot
- nom_prestataire_snapshot
- sous_total_ligne

#### DEMANDE_EVENEMENT
- id_demande_evenement
- id_utilisateur
- id_panier
- reference_demande
- type_evenement
- date_evenement
- lieu_evenement
- ville_evenement
- nombre_invites
- budget_indicatif
- commentaire_client
- statut_demande
- date_creation
- date_modification

#### DEVIS
- id_devis
- id_demande_evenement
- reference_devis
- montant_total
- commentaire_admin
- statut_devis
- date_emission
- date_validite
- date_creation
- date_modification

#### LIGNE_DEVIS
- id_ligne_devis
- id_devis
- id_prestation
- designation
- quantite
- prix_unitaire
- sous_total_ligne
- commentaire_ligne

#### HISTORIQUE_STATUT
- id_historique_statut
- type_objet
- id_objet
- ancien_statut
- nouveau_statut
- commentaire
- date_changement
- created_by

---

### 2.2 Associations et cardinalités

#### UTILISATEUR — POSSEDE — PRESTATAIRE
- 0,1 utilisateur possède 0,1 fiche prestataire.
- 1 prestataire appartient à 1 seul utilisateur.

#### PRESTATAIRE — PROPOSE — PRESTATION
- 1 prestataire propose 0,n prestations.
- 1 prestation appartient à 1 seul prestataire.

#### CATEGORIE_PRESTATION — CLASSE — PRESTATION
- 1 catégorie classe 0,n prestations.
- 1 prestation appartient à 1 seule catégorie.

#### PRESTATION — CONTIENT — MEDIA
- 1 prestation contient 0,n médias.
- 1 média appartient à 1 seule prestation.

#### UTILISATEUR — POSSEDE — PANIER
- 1 utilisateur possède 0,n paniers.
- 1 panier appartient à 1 seul utilisateur.

#### PANIER — CONTIENT — LIGNE_PANIER
- 1 panier contient 1,n lignes panier.
- 1 ligne panier appartient à 1 seul panier.

#### PRESTATION — FIGURE_DANS — LIGNE_PANIER
- 1 prestation peut figurer dans 0,n lignes panier.
- 1 ligne panier référence 1 seule prestation.

#### UTILISATEUR — CREE — DEMANDE_EVENEMENT
- 1 utilisateur crée 0,n demandes événement.
- 1 demande événement appartient à 1 seul utilisateur.

#### PANIER — DONNE_LIEU_A — DEMANDE_EVENEMENT
- 1 panier donne lieu à 0,1 demande événement.
- 1 demande événement provient d’1 seul panier.

#### DEMANDE_EVENEMENT — GENERE — DEVIS
- 1 demande événement génère 0,1 devis.
- 1 devis appartient à 1 seule demande.

#### DEVIS — DETAILLE — LIGNE_DEVIS
- 1 devis détaille 1,n lignes devis.
- 1 ligne devis appartient à 1 seul devis.

#### PRESTATION — APPARAIT_DANS — LIGNE_DEVIS
- 1 prestation peut apparaître dans 0,n lignes devis.
- 1 ligne devis référence 1 seule prestation.

#### UTILISATEUR — PRODUIT — HISTORIQUE_STATUT
- 1 utilisateur admin produit 0,n historiques.
- 1 historique est créé par 0,1 utilisateur.

---

## 3. MLD — Modèle Logique de Données

### UTILISATEUR
```text
UTILISATEUR(
  id_utilisateur PK,
  role_utilisateur,
  prenom,
  nom,
  email UNIQUE,
  telephone,
  mot_de_passe_hash,
  actif,
  date_creation,
  date_modification
)
```

### PRESTATAIRE
```text
PRESTATAIRE(
  id_prestataire PK,
  id_utilisateur FK UNIQUE,
  nom_commercial,
  raison_sociale,
  description,
  adresse,
  ville,
  pays,
  site_web,
  email_contact,
  telephone_contact,
  statut_validation,
  date_creation,
  date_modification
)
```

### CATEGORIE_PRESTATION
```text
CATEGORIE_PRESTATION(
  id_categorie PK,
  nom_categorie,
  description,
  actif,
  ordre_affichage
)
```

### PRESTATION
```text
PRESTATION(
  id_prestation PK,
  id_prestataire FK,
  id_categorie FK,
  titre_prestation,
  description_prestation,
  prix_unitaire,
  unite_prix,
  prix_minimum,
  conditions_tarifaires,
  delai_reservation,
  actif,
  date_creation,
  date_modification
)
```

### MEDIA
```text
MEDIA(
  id_media PK,
  id_prestation FK,
  type_media,
  url_media,
  titre_media,
  description_media,
  ordre_affichage,
  principal,
  date_creation
)
```

### PANIER
```text
PANIER(
  id_panier PK,
  id_utilisateur FK,
  statut_panier,
  date_creation,
  date_modification
)
```

### LIGNE_PANIER
```text
LIGNE_PANIER(
  id_ligne_panier PK,
  id_panier FK,
  id_prestation FK,
  quantite,
  prix_snapshot,
  titre_snapshot,
  nom_prestataire_snapshot,
  sous_total_ligne
)
```

### DEMANDE_EVENEMENT
```text
DEMANDE_EVENEMENT(
  id_demande_evenement PK,
  id_utilisateur FK,
  id_panier FK UNIQUE,
  reference_demande UNIQUE,
  type_evenement,
  date_evenement,
  lieu_evenement,
  ville_evenement,
  nombre_invites,
  budget_indicatif,
  commentaire_client,
  statut_demande,
  date_creation,
  date_modification
)
```

### DEVIS
```text
DEVIS(
  id_devis PK,
  id_demande_evenement FK UNIQUE,
  reference_devis UNIQUE,
  montant_total,
  commentaire_admin,
  statut_devis,
  date_emission,
  date_validite,
  date_creation,
  date_modification
)
```

### LIGNE_DEVIS
```text
LIGNE_DEVIS(
  id_ligne_devis PK,
  id_devis FK,
  id_prestation FK,
  designation,
  quantite,
  prix_unitaire,
  sous_total_ligne,
  commentaire_ligne
)
```

### HISTORIQUE_STATUT
```text
HISTORIQUE_STATUT(
  id_historique_statut PK,
  type_objet,
  id_objet,
  ancien_statut,
  nouveau_statut,
  commentaire,
  date_changement,
  created_by FK
)
```

---

## 4. MPD — Modèle Physique de Données MySQL

### 4.1 Conventions
- PK en `BIGINT UNSIGNED AUTO_INCREMENT`.
- `VARCHAR(190)` pour les emails, références et champs courts.
- `TEXT` pour les descriptions.
- `DECIMAL(10,2)` pour les montants.
- `INT UNSIGNED` pour les quantités.
- `DATETIME` pour les dates.
- moteur `InnoDB`.

### 4.2 Tables MySQL

#### utilisateurs
```sql
CREATE TABLE utilisateurs (
  id_utilisateur BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_utilisateur VARCHAR(20) NOT NULL,
  prenom VARCHAR(100) NOT NULL,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  telephone VARCHAR(30) NULL,
  mot_de_passe_hash VARCHAR(255) NOT NULL,
  actif TINYINT(1) NOT NULL DEFAULT 1,
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_modification DATETIME NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### prestataires
```sql
CREATE TABLE prestataires (
  id_prestataire BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_utilisateur BIGINT UNSIGNED NOT NULL UNIQUE,
  nom_commercial VARCHAR(150) NOT NULL,
  raison_sociale VARCHAR(150) NULL,
  description TEXT NULL,
  adresse VARCHAR(255) NULL,
  ville VARCHAR(120) NULL,
  pays VARCHAR(120) NULL,
  site_web VARCHAR(255) NULL,
  email_contact VARCHAR(190) NULL,
  telephone_contact VARCHAR(30) NULL,
  statut_validation VARCHAR(30) NOT NULL,
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_modification DATETIME NULL DEFAULT NULL,
  CONSTRAINT fk_prestataires_utilisateur FOREIGN KEY (id_utilisateur)
    REFERENCES utilisateurs(id_utilisateur)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### categories_prestations
```sql
CREATE TABLE categories_prestations (
  id_categorie BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nom_categorie VARCHAR(120) NOT NULL,
  description TEXT NULL,
  actif TINYINT(1) NOT NULL DEFAULT 1,
  ordre_affichage INT UNSIGNED NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### prestations
```sql
CREATE TABLE prestations (
  id_prestation BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_prestataire BIGINT UNSIGNED NOT NULL,
  id_categorie BIGINT UNSIGNED NOT NULL,
  titre_prestation VARCHAR(190) NOT NULL,
  description_prestation TEXT NULL,
  prix_unitaire DECIMAL(10,2) NOT NULL,
  unite_prix VARCHAR(50) NOT NULL,
  prix_minimum DECIMAL(10,2) NULL,
  conditions_tarifaires TEXT NULL,
  delai_reservation INT UNSIGNED NULL,
  actif TINYINT(1) NOT NULL DEFAULT 1,
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_modification DATETIME NULL DEFAULT NULL,
  CONSTRAINT fk_prestations_prestataire FOREIGN KEY (id_prestataire)
    REFERENCES prestataires(id_prestataire)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_prestations_categorie FOREIGN KEY (id_categorie)
    REFERENCES categories_prestations(id_categorie)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### media
```sql
CREATE TABLE media (
  id_media BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_prestation BIGINT UNSIGNED NOT NULL,
  type_media VARCHAR(20) NOT NULL,
  url_media VARCHAR(255) NOT NULL,
  titre_media VARCHAR(150) NULL,
  description_media TEXT NULL,
  ordre_affichage INT UNSIGNED NULL,
  principal TINYINT(1) NOT NULL DEFAULT 0,
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_media_prestation FOREIGN KEY (id_prestation)
    REFERENCES prestations(id_prestation)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### panier
```sql
CREATE TABLE panier (
  id_panier BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_utilisateur BIGINT UNSIGNED NOT NULL,
  statut_panier VARCHAR(30) NOT NULL,
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_modification DATETIME NULL DEFAULT NULL,
  CONSTRAINT fk_panier_utilisateur FOREIGN KEY (id_utilisateur)
    REFERENCES utilisateurs(id_utilisateur)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### ligne_panier
```sql
CREATE TABLE ligne_panier (
  id_ligne_panier BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_panier BIGINT UNSIGNED NOT NULL,
  id_prestation BIGINT UNSIGNED NOT NULL,
  quantite INT UNSIGNED NOT NULL,
  prix_snapshot DECIMAL(10,2) NOT NULL,
  titre_snapshot VARCHAR(190) NOT NULL,
  nom_prestataire_snapshot VARCHAR(150) NOT NULL,
  sous_total_ligne DECIMAL(10,2) NOT NULL,
  CONSTRAINT fk_ligne_panier_panier FOREIGN KEY (id_panier)
    REFERENCES panier(id_panier)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_ligne_panier_prestation FOREIGN KEY (id_prestation)
    REFERENCES prestations(id_prestation)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### demande_evenement
```sql
CREATE TABLE demande_evenement (
  id_demande_evenement BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_utilisateur BIGINT UNSIGNED NOT NULL,
  id_panier BIGINT UNSIGNED NOT NULL UNIQUE,
  reference_demande VARCHAR(50) NOT NULL UNIQUE,
  type_evenement VARCHAR(120) NOT NULL,
  date_evenement DATETIME NOT NULL,
  lieu_evenement VARCHAR(255) NOT NULL,
  ville_evenement VARCHAR(120) NOT NULL,
  nombre_invites INT UNSIGNED NULL,
  budget_indicatif DECIMAL(10,2) NULL,
  commentaire_client TEXT NULL,
  statut_demande VARCHAR(30) NOT NULL,
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_modification DATETIME NULL DEFAULT NULL,
  CONSTRAINT fk_demande_utilisateur FOREIGN KEY (id_utilisateur)
    REFERENCES utilisateurs(id_utilisateur)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_demande_panier FOREIGN KEY (id_panier)
    REFERENCES panier(id_panier)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### devis
```sql
CREATE TABLE devis (
  id_devis BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_demande_evenement BIGINT UNSIGNED NOT NULL UNIQUE,
  reference_devis VARCHAR(50) NOT NULL UNIQUE,
  montant_total DECIMAL(10,2) NOT NULL,
  commentaire_admin TEXT NULL,
  statut_devis VARCHAR(30) NOT NULL,
  date_emission DATETIME NOT NULL,
  date_validite DATETIME NOT NULL,
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_modification DATETIME NULL DEFAULT NULL,
  CONSTRAINT fk_devis_demande FOREIGN KEY (id_demande_evenement)
    REFERENCES demande_evenement(id_demande_evenement)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### ligne_devis
```sql
CREATE TABLE ligne_devis (
  id_ligne_devis BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_devis BIGINT UNSIGNED NOT NULL,
  id_prestation BIGINT UNSIGNED NOT NULL,
  designation VARCHAR(190) NOT NULL,
  quantite INT UNSIGNED NOT NULL,
  prix_unitaire DECIMAL(10,2) NOT NULL,
  sous_total_ligne DECIMAL(10,2) NOT NULL,
  commentaire_ligne TEXT NULL,
  CONSTRAINT fk_ligne_devis_devis FOREIGN KEY (id_devis)
    REFERENCES devis(id_devis)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_ligne_devis_prestation FOREIGN KEY (id_prestation)
    REFERENCES prestations(id_prestation)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### historique_statut
```sql
CREATE TABLE historique_statut (
  id_historique_statut BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  type_objet VARCHAR(30) NOT NULL,
  id_objet BIGINT UNSIGNED NOT NULL,
  ancien_statut VARCHAR(30) NULL,
  nouveau_statut VARCHAR(30) NOT NULL,
  commentaire TEXT NULL,
  date_changement DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_by BIGINT UNSIGNED NULL,
  CONSTRAINT fk_historique_created_by FOREIGN KEY (created_by)
    REFERENCES utilisateurs(id_utilisateur)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 5. Ordre logique de création

1. utilisateurs
2. prestataires
3. categories_prestations
4. prestations
5. media
6. panier
7. ligne_panier
8. demande_evenement
9. devis
10. ligne_devis
11. historique_statut

---

## 6. Notes de cohérence

- un seul panier actif peut exister par utilisateur si on le décide ensuite par règle métier ;
- la demande événementielle est liée à un panier déjà constitué ;
- le devis est global, il contient plusieurs lignes issues du panier ;
- les médias sont gérés par l’admin et rattachés aux prestations ;
- la structure est prête pour le MVC front/back minimaliste.

