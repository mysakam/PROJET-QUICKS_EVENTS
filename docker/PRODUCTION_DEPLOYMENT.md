# Déploiement production QuickEvents

## 1. Préparer le serveur

1. Installer Ubuntu 22.04 ou 24.04 sur un VPS.
2. Installer Docker et Docker Compose.
3. Ouvrir les ports 80 et 443 dans le pare-feu.
4. Installer Git.

## 2. Déployer le code

1. Cloner le dépôt sur le serveur.
2. Copier `.env.production.example` vers `.env.production`.
3. Modifier les mots de passe, `APP_DOMAIN`, `ADMIN_DOMAIN`, `ACME_EMAIL` et les variables sensibles.

## 3. Lancer l'application

Commande recommandée :

```bash
bash tools/deploy_prod.sh
```

Commande équivalente :

```bash
docker compose -f docker-compose.prod.yml --env-file .env.production up -d --build
```

Le script crée automatiquement `docker/traefik/acme.json`, utilisé par Let's Encrypt.

## 4. Vérifier l'état

```bash
docker compose -f docker-compose.prod.yml --env-file .env.production ps
docker compose -f docker-compose.prod.yml --env-file .env.production logs -f
```

## 5. HTTPS et domaines

Cette version intègre Traefik avec certificats Let's Encrypt automatiques.

Pré-requis :

1. `APP_DOMAIN` doit pointer vers l'IP du VPS.
2. `ADMIN_DOMAIN` doit pointer vers la même IP.
3. Les ports 80 et 443 doivent être ouverts.
4. Le serveur doit être accessible publiquement depuis Internet.

Routage prévu :

1. `https://APP_DOMAIN` vers le front.
2. `https://ADMIN_DOMAIN` vers le back.
3. Redirection automatique HTTP vers HTTPS.

## 6. Bonnes pratiques

1. Ne pas utiliser `seeds.sql` en production publique.
2. Sauvegarder la base MySQL chaque jour.
3. Désactiver tout mode debug.
4. Restreindre l'accès SSH par clé.
5. Mettre à jour régulièrement le serveur et les images.

## 7. Mise à jour

```bash
git pull
bash tools/deploy_prod.sh
```

## 8. Retour arrière

1. Restaurer le dépôt sur le commit stable précédent.
2. Restaurer le dump SQL si nécessaire.
3. Relancer `bash tools/deploy_prod.sh`.
