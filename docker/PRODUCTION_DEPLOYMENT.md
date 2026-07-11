# Déploiement production QuickEvents

## 1. Préparer le serveur

1. Installer Ubuntu 22.04 ou 24.04 sur un VPS.
2. Installer Docker et Docker Compose.
3. Ouvrir les ports 80 et 443 dans le pare-feu.
4. Installer Git.

## 2. Déployer le code

1. Cloner le dépôt sur le serveur.
2. Copier `.env.production.example` vers `.env.production`.
3. Modifier les mots de passe, le domaine et les variables sensibles.

## 3. Lancer l'application

Commande recommandée :

```bash
bash tools/deploy_prod.sh
```

Commande équivalente :

```bash
docker compose -f docker-compose.prod.yml --env-file .env.production up -d --build
```

## 4. Vérifier l'état

```bash
docker compose -f docker-compose.prod.yml --env-file .env.production ps
docker compose -f docker-compose.prod.yml --env-file .env.production logs -f
```

## 5. Ajouter HTTPS

Cette version fournit le reverse proxy HTTP. Pour la production Internet, il faut ajouter :

1. un nom de domaine pointant sur le VPS ;
2. un certificat TLS ;
3. une redirection HTTP -> HTTPS.

Le plus simple est d'ajouter Traefik ou Nginx Proxy Manager avec Let's Encrypt.

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