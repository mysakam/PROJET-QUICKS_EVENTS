#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT_DIR"

if [[ ! -f .env.production ]]; then
  echo "Missing .env.production. Copy .env.production.example first." >&2
  exit 1
fi

docker compose -f docker-compose.prod.yml --env-file .env.production pull || true
docker compose -f docker-compose.prod.yml --env-file .env.production up -d --build
docker compose -f docker-compose.prod.yml --env-file .env.production ps

echo
echo "Deployment finished."
echo "Check: docker compose -f docker-compose.prod.yml --env-file .env.production logs -f"