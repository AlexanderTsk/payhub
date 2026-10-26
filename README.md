# PayHub

PayHub is a mini payment platform built with Symfony. Merchants accept payments through a REST API. PayHub talks to an external payment provider, keeps merchant balances in a double-entry ledger, handles payouts and notifies merchants via webhooks.

## Tech stack

**Now:** PHP 8.4 (FPM, Alpine), Symfony 7.4 LTS, PostgreSQL 17, nginx, Docker Compose, Xdebug 3, PHP-CS-Fixer, PHPStan.

**Planned:** Doctrine ORM 3, Redis, RabbitMQ (Symfony Messenger), Elasticsearch, Mercure, PHPUnit, GitHub Actions, Kubernetes.

## Quick start

Requirements: Docker Desktop (or another Docker engine with Compose v2) and GNU Make.

```bash
git clone <repo-url> payhub && cd payhub
cp .env.example .env              # Compose variables: DB credentials, Xdebug mode
make build
make up
make composer c="install"         # install PHP dependencies inside the container
```

Open http://localhost:8080 — you should see the Symfony welcome page (HTTP 404 is expected: there are no routes yet).

## Make commands

Run `make help` to see the commands info.

## Debugging with Xdebug

Xdebug is installed in the dev image but **off** by default, so requests stay fast.

1. Start the stack in debug mode:
   ```bash
   make up XDEBUG_MODE=debug
   ```
   Run `make up` again later to switch it back off.
2. In VS Code install the **PHP Debug** extension (by Xdebug), open the repository root and start **Listen for Xdebug** (port 9003). Path mapping `/app` → `payhub/` is already configured in `.vscode/launch.json`.
3. Set a breakpoint and add the trigger to the request: http://localhost:8080/?XDEBUG_TRIGGER=1 (or use the "Xdebug Helper" browser extension).

## Production image

```bash
docker build -f docker/php/Dockerfile --target prod -t payhub-php:prod .
docker run --rm payhub-php:prod php bin/console about
```