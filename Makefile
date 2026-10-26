.DEFAULT_GOAL := help

help:
	@echo "Makefile commands:"
	@echo "  build      Build the Docker images"
	@echo "  up         Start the containers (make up XDEBUG_MODE=debug to enable Xdebug)"
	@echo "  down       Stop and remove the containers"
	@echo "  restart    Restart the containers"
	@echo "  logs       Show the container logs"
	@echo "  ps         Show the container status"
	@echo "  sh         Open a shell in the PHP container"
	@echo "  composer   Run a composer command: make composer c=\"...\""
	@echo "  console    Run a Symfony console command: make console c=\"...\""
	@echo "  cc         Clear the Symfony cache"
	@echo "  test       Run tests (not implemented yet)"
	@echo "  cs         Run PHP CS Fixer in dry-run mode"
	@echo "  cs-fix     Run PHP CS Fixer to fix issues"
	@echo "  stan       Run PHPStan static analysis"
	@echo "  lint       Run all linters (composer validate, cs, stan)"
	@echo "  help       Show this help message"

build:
	docker compose build

up:
	docker compose up -d --wait

down: 
	docker compose down

restart: 
	make down
	make up

logs:
	docker compose logs $(s)

cs:
	docker compose exec php vendor/bin/php-cs-fixer fix --dry-run --diff

cs-fix:
	docker compose exec php vendor/bin/php-cs-fixer fix

stan:
	docker compose exec php vendor/bin/phpstan analyse

lint:
	docker compose exec php composer validate --strict 
	make cs
	make stan

ps: 
	docker compose ps

sh:
	docker compose exec php sh

composer: 
	docker compose exec php composer $(c)

console: 
	docker compose exec php bin/console $(c)

cc: 
	docker compose exec php bin/console cache:clear

test: 
	@echo "Not implemented yet"

.PHONY: build up down restart logs ps sh composer console cc test help cs cs-fix stan lint
