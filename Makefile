# Setup ————————————————————————————————————————————————————————————————————————————————————————————————————————————————
SHELL := /bin/bash

COMPOSER_OPTIONS = --no-interaction --prefer-dist --optimize-autoloader
IS_DOCKER = $(shell docker info > /dev/null 2>&1 && echo 1)
ENV_FILE = .env

ifneq (,$(wildcard .env.local))
	include .env.local
	ENV_FILE = .env.local
endif

ifeq ($(IS_DOCKER), 1)
	DC = docker-compose --env-file ./${ENV_FILE}
	DE = $(DC) exec advent

	CONSOLE     = $(DE) bin/console
	COMPOSER    = $(DE) composer
	PHP         = $(DE) php
else
	CONSOLE     = bin/console
	COMPOSER    = composer
	PHP         = php
endif

ifndef APP_ENV
	APP_ENV = dev
endif

ifdef PUBLIC_PATH
	export PUBLIC_PATH
endif

ifeq ($(APP_ENV), prod)
	COMPOSER_OPTIONS = --no-interaction --prefer-dist --optimize-autoloader --no-dev
endif

.SILENT:
.DEFAULT_GOAL := help

# Colors
COLOR_RESET   = \033[0m
COLOR_INFO    = \033[32m
COLOR_COMMENT = \033[33m

.DEFAULT_GOAL := help

##—— Makefile —————————————————————————————————————————————————————————————————————————————————————————————————————————
help: ## Outputs this help screen
	printf "${DE} ${COLOR_COMMENT}php: ${PHP}  \nuser: ${USER} \ngroup: ${GROUP} \nwith docker: $(IS_DOCKER)\n\n"
	@grep -E '(^[a-zA-Z0-9\.@_-]+:.*?##.*$$)|(^##)' $(firstword  $(MAKEFILE_LIST)) | awk 'BEGIN {FS = ":.*?## "}{printf "${COLOR_INFO}%-30s${COLOR_RESET} %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


##—— Advent Of Code —————————————————————————————————————————————————————————————————————————————————————————————————————
resolve: ## Resolve current day
	$(CONSOLE) puzzle:resolve

resolve-test: ## Resolve current day with test input
	$(CONSOLE) puzzle:resolve --test

puzzle: ## Create structure and data for current day
	$(CONSOLE) puzzle:make

##—— Install ——————————————————————————————————————————————————————————————————————————————————————————————————————————
install: app-build ## Install apps based on APP_ENV

app-build: vendor/autoload.php

.PHONY: app-build install

##—— Quality assurance ————————————————————————————————————————————————————————————————————————————————————————————————

format: vendor/autoload.php  ## Format code with php-cs-fixer
	$(PHP) vendor/bin/php-cs-fixer fix

phpstan: phpstan.neon.dist ## Run PHPStan (the configuration must be defined in phpstan.neon)
	$(PHP) vendor/bin/phpstan analyse  --xdebug

psalm: psalm.xml ## Run Psalm
	$(PHP) vendor/bin/psalm  --no-cache

rector: ## Run rector
	$(PHP)  vendor/bin/rector process src

rector-dry: ## Run rector in dry mode
	$(PHP)  vendor/bin/rector process src --dry-run
.PHONY: psalm phpstan format

qa-all:
	make format
	make psalm
	make phpstan
	make rector-dry

##—— Docker  ——————————————————————————————————————————————————————————————————————————————————————————————————————————

up:				## Start service, rebuild if necessary
	$(DC) up -d

ps:				## docker ps service
	$(DC) ps

build:			## Build The Image
	$(DC) build

down:			## Down service and do clean up
	$(DC) down

start:			## Start Container
	$(DC) start

stop:			## Stop Container
	$(DC) stop

logs:			## Tail container logs with -n 1000
	@$(DC) logs --follow --tail=1000

images:		## Show Image created by this Makefile (or $(DC) in docker)
	@$(DC) images

bash:			## Enter container shell
	@$(DE) /bin/bash

restart:			## Restart container
	@$(DC) restart

rm:				## Remove current container
	@$(DC) rm -f

.PHONY: build start stop logs restart bash up rm  ps

# -----------------------------------
# Dépendances
# -----------------------------------

composer.lock: composer.json
	$(COMPOSER) update --lock $(COMPOSER_OPTIONS)

vendor/autoload.php: composer.lock
	$(COMPOSER) install $(COMPOSER_OPTIONS)
	touch vendor/autoload.php

node_modules: yarn.lock
	$(YARN) install
	@touch -c node_modules

yarn.lock: package.json
	$(YARN) upgrade

var/dump:
	mkdir var/dump