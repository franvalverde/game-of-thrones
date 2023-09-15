DOCKER_NAMESPACE := $(shell basename $(CURDIR) | tr A-Z a-z)
DATE := $(shell date +%Y)

DOCKER_COMPOSE = docker compose -p ${DOCKER_NAMESPACE} -f .deployment/docker/docker-compose.yml
EXEC_APP = $(DOCKER_COMPOSE) run -T --user 1000:1000 --rm app sh -c

.DEFAULT_GOAL := help

##@ Helpers

.PHONY: help
help: ## Display help
	@awk 'BEGIN {FS = ":.*##"; printf "\n\033[1;34m${DOCKER_NAMESPACE} - Whalar Backend Recruitment Test\033[0m\nCopyright (c) ${DATE} Fran Valverde\n \nUsage:\n  make \033[1;34m<target>\033[0m\n"} /^[a-zA-Z_-]+:.*?##/ { printf "  \033[1;34m%-25s\033[0m %s\n", $$1, $$2 } /^##@/ { printf "\n\033[1m%s\033[0m\n", substr($$0, 5) } ' $(MAKEFILE_LIST)

##@ Main commands

.PHONY: initialize
initialize: set-hooks copy-environment stop clean prepare start composer-install db-init ## Initialize this project from scratch

.PHONY: start
start: ## Start this project
	$(DOCKER_COMPOSE) up -d --remove-orphans

.PHONY: stop
stop: ## Stop this project
	$(DOCKER_COMPOSE) stop

.PHONY: clean
clean: ## Clean vendors, cache, logs, assets, reports, etc.
	@rm -rf report/* var/*

.PHONE: set-hooks
set-hooks:
	@git config core.hooksPath .deployment/hooks

.PHONY: copy-environment
copy-environment: ## Copy environment files
	@cp .env.dist .env
	@cp .env .deployment/docker/.env

.PHONY: prepare
prepare:
	@$(DOCKER_COMPOSE) pull --quiet --ignore-pull-failures
	@$(DOCKER_COMPOSE) build --pull

##@ Packages

.PHONY: composer-install
composer-install: ## Install Composer dependencies
	$(EXEC_APP) "composer install"

.PHONY: composer-update
composer-update: ## Update Composer dependencies
	$(EXEC_APP) "composer update"

.PHONY: composer-validate
composer-validate: ## Validate format of composer
	$(EXEC_APP) "composer validate --no-check-lock --strict composer.json"

##@ Database

.PHONY: db-init
db-init: db-fresh messenger-setup ## Initialize database and setup Messenger transports

.PHONY: db-fresh
db-fresh: ## Drop the database and create a new one with all migrations
	$(EXEC_APP) "./bin/console doctrine:database:drop --force --if-exists"
	$(EXEC_APP) "./bin/console doctrine:database:create"
	$(EXEC_APP) "./bin/console doctrine:migrations:migrate --no-interaction"

.PHONY: db-migrate
db-migrate: ## Launch database migrations
	$(EXEC_APP) "./bin/console doctrine:migrations:migrate --no-interaction"

.PHONY: db-diff
db-diff: db-init ## Generate a migration by comparing your current database to your mapping information
	$(EXEC_APP) "./bin/console doctrine:migration:diff"

.PHONY: db-validate
db-validate: ## Validate Doctrine mapping files
	$(EXEC_APP) "php bin/console doctrine:schema:validate"

##@ Messenger

.PHONY: messenger-setup
messenger-setup: ## Setup Messenger transports
	$(EXEC_APP) "./bin/console messenger:setup-transports"

##@ Code analysis

.PHONY: lint-check
lint-check: ## Analyse code and fix errors
	$(EXEC_APP) "php -d memory_limit=-1 ./bin/phpcs --standard=phpcs.xml.dist --report-full --report-summary -s src tests"
	$(EXEC_APP) "php -d memory_limit=-1 ./bin/php-cs-fixer fix --no-interaction --dry-run --diff -v"

.PHONY: lint-fix
lint-fix: ## Analyse code and fix errors
	$(EXEC_APP) "php -d memory_limit=-1 ./bin/phpcbf --standard=phpcs.xml.dist -s src tests"
	$(EXEC_APP) "php -d memory_limit=-1 ./bin/php-cs-fixer fix --no-interaction --diff -v"

##@ Test

.PHONY: unit-test
unit-test: clean ## Execute unit tests
	$(EXEC_APP) "php bin/phpunit --no-coverage --testsuite='unit'"

.PHONY: unit-test-coverage
unit-test-coverage: clean ## Execute unit tests with coverage
	$(EXEC_APP) "XDEBUG_MODE=coverage phpunit tests/Unit"
	@if command -v xdg-open; then xdg-open ./report/html/index.html; fi;

.PHONY: mutant-test
mutant-test: ## Execute mutant tests
	$(EXEC_APP) "php -d memory_limit=-1 bin/infection --show-mutations --threads=max"

TEST_SUITES := document management marketplace medical_test chat

.PHONY: acceptance-test
acceptance-test: ## Execute acceptance tests
	$(EXEC_APP) "bin/console doctrine:database:drop --if-exists --force --quiet --no-interaction --env=test"
	$(EXEC_APP) "bin/console doctrine:database:create --quiet --no-interaction --env=test"
	$(EXEC_APP) "bin/console doctrine:migrations:migrate --quiet --no-interaction --env=test"
	$(EXEC_APP) "php -d memory_limit=-1 bin/behat --suite=core --no-snippets --strict"
