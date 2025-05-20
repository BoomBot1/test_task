include .env

start:
	make check-dotenv;
	make set-deployment-name;
	echo "This is v3 template!"

init:
	make check-dotenv;
	make fix-permissions-local;
	make build;
	make up;
	make optimize;
	make key-generate;
	make storage-link;
	make fix-permissions-local;

# ========================================
# DEPLOY AUTOMATIONS
# ========================================
build:
	make check-dotenv;
	docker compose -f docker-compose.yml build

up:
	make check-dotenv;
	docker compose -f docker-compose.yml up -d

down:
	make check-dotenv;
	docker compose -f docker-compose.yml down

up-dev:
	make check-dotenv;
	make fix-permissions;
	docker compose -f docker-compose-dev.yml up -d

down-dev:
	make check-dotenv;
	make fix-permissions;
	docker compose -f docker-compose-dev.yml down

up-prod:
	make check-dotenv;
	make fix-permissions;
	docker compose -f docker-compose-prod.yml up -d

down-prod:
	make check-dotenv;
	make fix-permissions;
	docker compose -f docker-compose-prod.yml down

ul: up
dl: down
bl: build

# ========================================
# UTILITY AUTOMATIONS
# ========================================
set-deployment-name:
ifeq ($(shell uname),Darwin) # Check if the OS is macOS (BSD)
	sed -i '' -e "s/laravel-basic-backend/${DEPLOYMENT_NAME}/g" deploy/nginx/*/*
	sed -i '' -e "s/laravel-basic-backend/${DEPLOYMENT_NAME}/g" docker-compose*.yml
	sed -i '' -e "s/laravel-basic-backend/${DEPLOYMENT_NAME}/g" template-.gitlab-ci.yml
	sed -i '' -e "s/laravel-basic-backend/${DEPLOYMENT_NAME}/g" .env.example
	sed -i '' -e "s/laravel-basic-backend/${DEPLOYMENT_NAME}/g" .env
else
	sed -i -e "s/laravel-basic-backend/${DEPLOYMENT_NAME}/g" deploy/nginx/*/*
	sed -i -e "s/laravel-basic-backend/${DEPLOYMENT_NAME}/g" docker-compose*.yml
	sed -i -e "s/laravel-basic-backend/${DEPLOYMENT_NAME}/g" template-.gitlab-ci.yml
	sed -i -e "s/laravel-basic-backend/${DEPLOYMENT_NAME}/g" .env.example
	sed -i -e "s/laravel-basic-backend/${DEPLOYMENT_NAME}/g" .env
endif

fix-permissions:
	make git-config;
	chown -R www-data:www-data ./ && chmod -R 775 ./

fix-permissions-local:
	make git-config;
ifeq ($(shell uname),Darwin) # Check if the OS is macOS (BSD)
	echo "Skipping fix-permissions-local on macOS"
else
	sudo chown -R ${USER}:${USER} ./ && sudo chmod -R 777 ./
endif

git-config:
	git config core.fileMode false

optimize:
	make check-dotenv;
	docker exec -it ${DEPLOYMENT_NAME}-fpm bash -c "composer install"
	docker exec -it ${DEPLOYMENT_NAME}-fpm bash -c "composer du"
	docker exec -it ${DEPLOYMENT_NAME}-fpm bash -c "php artisan migrate"
	docker exec -it ${DEPLOYMENT_NAME}-fpm bash -c "php artisan optimize:clear"

key-generate:
	make check-dotenv;
	docker exec -it ${DEPLOYMENT_NAME}-fpm bash -c "php artisan key:generate"

storage-link:
	make check-dotenv;
	docker exec -it ${DEPLOYMENT_NAME}-fpm bash -c "php artisan storage:link"

check-dotenv:
	@test -e .env || (echo "[ ERROR ] - .env file not found!"; exit 1)

ide:
	php artisan ide-helper:generate
	php artisan ide-helper:eloquent
	php artisan ide-helper:models
	php artisan ide-helper:meta

fpm-terminal:
	docker exec -it ${DEPLOYMENT_NAME}-fpm bash

fpm-terminal-root:
	docker exec -u 0 -it ${DEPLOYMENT_NAME}-fpm bash

# ========================================
# LINTER AUTOMATIONS
# ========================================
pint:
	./vendor/bin/pint

pint-d:
	./vendor/bin/pint --dirty

# ========================================
# TESTS
# ========================================
test:
	php artisan config:clear
	php artisan test --stop-on-failure

test-pest:
	./vendor/bin/pest

local-refresh:
ifeq ($(shell grep '^APP_ENV=' .env | sed 's/APP_ENV=//'), local)
	rm -rf ./storage/app/public/*/
	php artisan optimize:clear
	php artisan cache:clear
	php artisan horizon:terminate
	php artisan horizon:clear-metrics
	php artisan migrate:fresh --seed
else
	echo "APP_ENV is not local. Exiting..."
	exit 1
endif
