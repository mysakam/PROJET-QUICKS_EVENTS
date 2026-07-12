# Stack technique du projet QuickEvents

Dans le cadre de ce projet, le choix de la stack technique a été orienté vers la fiabilité, la lisibilité du code et la facilité de déploiement. Le tableau ci-dessous présente les principaux outils, technologies et frameworks utilisés.

| Technologie / Outil               | Type                            | Rôle dans le projet                                                      | Justification                                                   |
| --------------------------------- | ------------------------------- | ------------------------------------------------------------------------ | --------------------------------------------------------------- |
| PHP 8.3                           | Langage backend                 | Développement de la logique métier, contrôleurs, modèles et sécurité     | Technologie adaptée au contexte web, stable et bien maîtrisée   |
| MVC maison                        | Architecture                    | Structuration du code (modèles, vues, contrôleurs)                       | Séparation claire des responsabilités, meilleure maintenabilité |
| Apache                            | Serveur web                     | Exécution de l’application PHP et gestion des routes côté conteneur      | Intégration native avec PHP, configuration simple               |
| MySQL 8.0                         | SGBD relationnel                | Stockage des données métiers (clients, prestations, devis, médias, etc.) | Fiable, performant, standard pour applications web              |
| HTML / CSS / JavaScript (vanilla) | Frontend                        | Interface utilisateur publique et administrative                         | Simplicité, légèreté, pas de dépendance framework frontend      |
| Composer                          | Gestionnaire de dépendances PHP | Gestion des bibliothèques et scripts de projet                           | Standard de l’écosystème PHP                                    |
| PHPUnit                           | Framework de tests              | Exécution des tests unitaires / smoke                                    | Amélioration de la qualité et limitation des régressions        |
| Runner HTTP personnalisé          | Outil de test fonctionnel       | Validation des parcours réels (auth, panier, devis, admin)               | Complète PHPUnit sur les scénarios bout-en-bout                 |
| Docker                            | Conteneurisation                | Isolation des services et homogénéité des environnements                 | Déploiement reproductible et portable                           |
| Docker Compose                    | Orchestration locale/prod       | Gestion multi-services (front, back, db, proxy)                          | Simplifie le lancement et la maintenance de la stack            |
| Traefik                           | Reverse proxy / TLS             | Routage des domaines et HTTPS via Let’s Encrypt                          | Automatisation des certificats, configuration moderne           |
| Let’s Encrypt                     | Autorité de certification       | Certificats SSL/TLS pour la mise en ligne sécurisée                      | Solution gratuite et standard pour HTTPS                        |
| Git                               | Versioning                      | Suivi de l’historique, collaboration, traçabilité                        | Indispensable pour la gestion de projet logiciel                |
| WAMP / WSL / Bash / PowerShell    | Environnement & outils système  | Développement local, commandes d’administration et scripts               | Facilite l’exécution et le diagnostic en environnement Windows  |

Cette stack offre un bon équilibre entre simplicité de mise en œuvre, robustesse technique et capacité d’évolution.
