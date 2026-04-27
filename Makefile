DOCKER_COMPOSE ?= docker compose -p abelohost
DOCKER_COMPOSE_UP ?= $(DOCKER_COMPOSE) up --force-recreate -d --no-deps --build --remove-orphans --timeout 1

run-infra:
	$(DOCKER_COMPOSE_UP) mysql
run-migrate-up:
	$(DOCKER_COMPOSE_UP) migrations && $(DOCKER_COMPOSE) run migrations composer migrate:install-up
run-app:
	ENV=production $(DOCKER_COMPOSE_UP) app
run-app-debug:
	PHP_INI_EXT=.debug PHP_IDE_CONFIG="serverName=abelohost" ENV=development $(DOCKER_COMPOSE_UP) app

run: run-infra run-migrate-up run-app