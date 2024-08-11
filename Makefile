DOCKER=docker
SAIL=vendor/bin/sail
CURRENT_UID=$(shell id -u)
CURRENT_GID=$(shell id -g)
PWD=$(shell pwd)

.PHONY: bash
bash:
	$(SAIL) bash

.PHONY: down
down:
	$(SAIL) down -v

.PHONY: up
up:
	$(SAIL) up -d --remove-orphans
	sleep 10

.PHONY: composer
composer:
	$(DOCKER) run --rm \
		-u "$(CURRENT_UID):$(CURRENT_GID)" \
		-v "$(PWD):/var/www/html" \
		-w /var/www/html \
		laravelsail/php83-composer:latest \
		composer install --ignore-platform-reqs

.PHONY: install
install:
	$(SAIL) artisan key:generate
	$(SAIL) artisan passport:keys
	$(SAIL) artisan migrate
	$(SAIL) artisan passport:client --personal --no-interaction
	$(SAIL) artisan db:seed

.PHONY: startup
startup:
	make composer
	make up
	make install

.PHONY: test
test:
	$(SAIL) artisan test
