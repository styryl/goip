#!/usr/bin/env make

COMPOSE_RUN = docker compose run --rm --user "$$(id -u):$$(id -g)" php

default: bash

build:
	docker compose build

install:
	$(COMPOSE_RUN) composer install

test:
	@make ecs
	@make phpmd
	@make phpstan
	@make phpunit

ecs:
	$(COMPOSE_RUN) sh -c 'vendor/bin/ecs check --config=ecs.php;'

ecs_fix:
	$(COMPOSE_RUN) sh -c 'vendor/bin/ecs check --config=ecs.php --fix;'

phpmd:
	$(COMPOSE_RUN) sh -c 'php vendor/bin/phpmd src ansi ruleset.xml'

phpstan:
	$(COMPOSE_RUN) sh -c 'vendor/bin/phpstan --memory-limit=256M analyse -c phpstan.neon'

phpunit:
	$(COMPOSE_RUN) sh -c 'vendor/bin/phpunit --testdox'

debug_server:
	docker compose up debug-server

bash:
	$(COMPOSE_RUN) sh
