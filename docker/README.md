# Docker QuickEvents

1. Copier .env.docker.example vers .env.
2. Lancer docker compose up -d --build.
3. Front : http://localhost:8080
4. Back : http://localhost:8081
5. Base MySQL exposée sur localhost:3307.

La base est initialisée automatiquement depuis back/database/schema.sql et back/database/seeds.sql au premier démarrage.
