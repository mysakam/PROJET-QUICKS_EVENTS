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
│   ├── PanierController.php
│   ├── DevisController.php
│   ├── ClientController.php
│   └── PrestataireController.php
├── models/
│   ├── User.php
│   ├── Prestation.php
│   ├── Panier.php
│   └── DemandeDevis.php
├── middleware/
│   ├── AuthMiddleware.php
│   └── GuestMiddleware.php
├── routes/
│   └── web.php
└── views/
    ├── layouts/
    │   ├── main.php
    │   └── auth.php
    ├── partials/
    │   ├── header.php
    │   ├── footer.php
    │   └── flash.php
    ├── home/
    │   └── index.php
    ├── auth/
    │   ├── login.php
    │   ├── register.php
    │   ├── forgot.php
    │   └── reset.php
    ├── catalogue/
    │   ├── index.php
    │   └── show.php
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
    │   └── show.php
    ├── prestations/
    │   ├── index.php
    │   ├── create.php
    │   └── edit.php
    ├── categories/
    │   ├── index.php
    │   ├── create.php
    │   └── edit.php
    └── devis/
        ├── index.php
        └── show.php
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
