# Docker QuickEvents

Guide detaille pas a pas : [docker/DEPLOYMENT_STEPS.md](DEPLOYMENT_STEPS.md)

1. Copier .env.docker.example vers .env.
2. Lancer docker compose up -d --build.
3. Front : http://localhost:8080
4. Back : http://localhost:8081
5. Base MySQL exposée sur localhost:3307.

La base est initialisée automatiquement depuis back/database/schema.sql et back/database/seeds.sql au premier démarrage.

## Production

1. Copier .env.production.example vers .env.production.
2. Ajuster les secrets, `APP_DOMAIN`, `ADMIN_DOMAIN` et `ACME_EMAIL`.
3. Lancer `bash tools/deploy_prod.sh`.
4. Voir la procédure détaillée dans docker/PRODUCTION_DEPLOYMENT.md.

La version production utilise Traefik avec HTTPS automatique via Let's Encrypt.
