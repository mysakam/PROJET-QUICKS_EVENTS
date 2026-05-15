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

```text
front/
├── public/
│   ├── index.php
│   └── .htaccess
├── assets/
│   ├── css/
│   │   ├── style.css
│   │   ├── auth.css
│   │   └── responsive.css
│   ├── js/
│   │   ├── main.js
│   │   ├── auth.js
│   │   ├── panier.js
│   │   └── devis.js
│   └── images/
│       ├── banners/
│       ├── prestations/
│       └── avatars/
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
│   ├── PrestataireController.php
│   ├── PrestationController.php
│   ├── PanierController.php
│   ├── DevisController.php
│   ├── ClientController.php
│   └── AccountController.php
├── models/
│   ├── User.php
│   ├── Prestataire.php
│   ├── Prestation.php
│   ├── Panier.php
│   ├── LignePanier.php
│   ├── DemandeDevis.php
│   └── Devis.php
├── middlewares/
│   ├── AuthMiddleware.php
│   ├── GuestMiddleware.php
│   ├── ClientMiddleware.php
│   └── PrestataireMiddleware.php
├── routes/
│   ├── web.php
│   ├── auth.php
│   ├── client.php
│   └── prestataire.php
├── views/
│   ├── layouts/
│   │   ├── main.php
│   │   └── auth.php
│   ├── partials/
│   │   ├── header.php
│   │   ├── footer.php
│   │   ├── navbar.php
│   │   └── flash.php
│   ├── home/
│   │   └── index.php
│   ├── auth/
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── forgot-password.php
│   │   └── reset-password.php
│   ├── catalogue/
│   │   ├── index.php
│   │   └── show.php
│   ├── prestataires/
│   │   └── show.php
│   ├── prestations/
│   │   └── show.php
│   ├── panier/
│   │   ├── index.php
│   │   └── recap.php
│   ├── devis/
│   │   ├── create.php
│   │   └── show.php
│   ├── client/
│   │   └── dashboard.php
│   └── prestataire/
│       └── dashboard.php
└── includes/
    ├── functions.php
    ├── csrf.php
    └── validation.php
```

---

## 4. Partie Back

La partie back gère l’administration générale, les utilisateurs, les prestataires, les prestations, les catégories et les devis.

```text
back/
├── public/
│   ├── index.php
│   └── .htaccess
├── assets/
│   ├── css/
│   │   ├── dashboard.css
│   │   └── admin.css
│   ├── js/
│   │   ├── admin.js
│   │   └── charts.js
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
│   ├── DashboardController.php
│   ├── UsersController.php
│   ├── PrestatairesController.php
│   ├── PrestationsController.php
│   ├── CategoriesController.php
│   ├── DevisController.php
│   └── SettingsController.php
├── models/
│   ├── User.php
│   ├── Prestataire.php
│   ├── Prestation.php
│   ├── CategoriePrestation.php
│   ├── DemandeDevis.php
│   ├── Devis.php
│   └── HistoriqueStatut.php
├── middlewares/
│   ├── AdminMiddleware.php
│   ├── AuthMiddleware.php
│   └── GuestMiddleware.php
├── routes/
│   ├── web.php
│   ├── auth.php
│   └── admin.php
├── views/
│   ├── layouts/
│   │   ├── admin.php
│   │   └── auth.php
│   ├── partials/
│   │   ├── sidebar.php
│   │   ├── topbar.php
│   │   ├── flash.php
│   │   └── table-actions.php
│   ├── auth/
│   │   └── login.php
│   ├── dashboard/
│   │   └── index.php
│   ├── users/
│   │   ├── index.php
│   │   ├── show.php
│   │   └── edit.php
│   ├── prestataires/
│   │   ├── index.php
│   │   ├── show.php
│   │   └── edit.php
│   ├── prestations/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── edit.php
│   ├── categories/
│   │   ├── index.php
│   │   ├── create.php
│   │   └── edit.php
│   └── devis/
│       ├── index.php
│       └── show.php
└── includes/
    ├── functions.php
    ├── csrf.php
    └── validation.php
```

---

## 5. Partie partagée

Le dossier `shared/` contient les éléments communs aux deux environnements pour éviter les doublons.

```text
shared/
├── helpers/
│   ├── url.php
│   ├── redirect.php
│   └── view.php
├── mail/
│   └── mailer.php
├── validation/
│   └── rules.php
└── templates/
    ├── header.php
    ├── footer.php
    └── flash.php
```

---

## 6. Base de données et fichiers communs

```text
database/
├── schema.sql
├── seeds.sql
├── migrations/
└── backups/

uploads/
├── users/
├── prestataires/
├── prestations/
└── devis/
```

---

## 7. Recommandation d’usage

- **Front** pour tout ce que voit et utilise l’utilisateur final.
- **Back** pour l’administration et la validation métier.
- **Shared** pour les fonctions, templates et validations communs.

Cette séparation rend le projet plus lisible et limite les collisions entre l’interface publique et l’interface admin.
