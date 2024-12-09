# Executables (local)
DOCKER_COMP = docker-compose

PHP_SERVICE = app
NODE_SERVICE = node
DB_SERVICE = mysql

# Make Makefile available for users without Docker setup
ifeq ($(APP_DOCKER), 0)
	PHP_CONT =
	DB_CONT =
	NODE_CONT =
else
	PHP_CONT = $(DOCKER_COMP) exec $(PHP_SERVICE)
	NODE_CONT = $(DOCKER_COMP) exec $(NODE_SERVICE)
	DB_CONT = $(DOCKER_COMP) exec $(DB_SERVICE)
endif

# Executables
PHP = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY = $(PHP_CONT) tests/Application/bin/console
SYMFONY_CLI = $(PHP_CONT) symfony
NPM = $(NODE_CONT) npm

# Executables: vendors
PHPUNIT = $(PHP) vendor/bin/phpunit
PHPSPEC = $(PHP) vendor/bin/phpspec
ECS     = $(PHP) vendor/bin/ecs

# Misc
.DEFAULT_GOAL := help

##
## —— Sylius Makefile 🦢 ———————————————————————————————————
##

help: ## Outputs help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


##
## —— Docker 🐳 ————————————————————————————————————————————————————————————————
##

start-containers: ## Start all containers
	$(DOCKER_COMP) up -d --remove-orphans

stop-containers: ## Stop all containers
	$(DOCKER_COMP) stop || exit 0

down-containers: ## Stop and remove containers
	$(DOCKER_COMP) down || exit 0

pull-containers: ## Pull containers from Docker hub
	$(DOCKER_COMP) pull --no-parallel --include-deps --ignore-pull-failures

build-containers: ## Build project
	$(DOCKER_COMP) build

logs: ## Show live logs
	$(DOCKER_COMP) logs --tail=30 --follow

sh: ## Connect to the PHP FPM container
	$(DOCKER_COMP) exec $(PHP_SERVICE) bash

ps: ## Display status of running containers
	$(DOCKER_COMP) ps

##
## —— Symfony 🎶️ ————————————————————————————————————————————————————————————————
##

security: ## Check security issues in project dependencies
	$(SYMFONY_CLI) security:check

##
## —— Composer 🎼 ———————————————————————————————————————————————————————————————
##

composer-install: ## Install project dependencies
	docker-compose run --rm app composer install --no-scripts --no-interaction --no-progress --no-suggest
	git clean -f -d
	git clean -f

composer-reinstall: ## Reinstall composer dependencies
	$(COMPOSER) clearcache
	rm -rf vendor/*
	make composer-install

composer-dump: ## Dump composer
	$(COMPOSER) dump-autoload --optimize --classmap-authoritative --no-interaction --quiet

composer-validate: ## Validate composer json and lock
	$(COMPOSER) validate --ansi --strict

##
## —— Database 📜 ————————————————————————————————————————————————————————————————
##

migrate:
	$(SYMFONY) doctrine:migrations:migrate

migrations-status:
	$(SYMFONY) doctrine:migrations:status

##
## —— Front 🎨 ————————————————————————————————————————————————————————————————
##

front-assets: ## Install all assets
	$(PHP_CONT) rm -rf tests/Application/public/bundles
	$(SYMFONY) sylius:install:assets --no-debug

front-watch: ## Build dev and watch project
	$(PHP_CONT) rm -rf public/build/*
	$(NPM) run watch

front-build: ## Build prod project
	$(PHP_CONT) rm -rf public/build/*
	$(NPM) run build:prod

front-lint: ## Run lint project
	$(NPM) run lint:js


##
## —— Generic 🧭 ————————————————————————————————————————————————————————————————
##

install-backend: ## Install backend
	$(SYMFONY) sylius:install --no-interaction
	$(SYMFONY) sylius:fixtures:load default --no-interaction

install-frontend: ## Install frontend
	$(NODE_CONT) yarn install --pure-lockfile
	$(NODE_CONT) sh -c "GULP_ENV=prod yarn build"

install: ## Install everything
	make composer-install
	make install-backend
	make install-frontend
	make front-assets

phpunit: ## Run phpunit
	vendor/bin/phpunit

phpspec: ## Run phpspec
	vendor/bin/phpspec run --ansi --no-interaction -f dot

phpstan: ## Run phpstan
	vendor/bin/phpstan analyse

psalm: ## Run psalm
	vendor/bin/psalm

behat: ## Run behat
	APP_ENV=test vendor/bin/behat --colors --strict --no-interaction -vvv -f progress

ci: install phpstan psalm phpunit phpspec behat

integration: install phpunit behat

static: install phpspec phpstan psalm
