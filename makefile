init:
	cp .env.example .env
	docker-compose build --no-cache app
	docker-compose up -d app
	docker-compose exec app composer install
	docker-compose exec app php artisan key:generate

up:
	docker-compose up -d app

build:
	docker-compose build app

build-no-cache:
	docker-compose build  --no-cache app

down:
	docker-compose down

exec:
	docker exec -it app /bin/bash

sniff:
	docker-compose exec app composer sniff

sniff-fix:
	docker-compose exec app composer sniff-fix

analyse:
	docker-compose exec app composer analyse

test:
	docker-compose exec app php -c ./disable-xdebug.ini ./vendor/bin/phpunit tests

coverage:
	docker-compose exec app php -dxdebug.mode=coverage ./vendor/bin/phpunit --coverage-clover='reports/coverage/coverage.xml' --coverage-html='reports/coverage'

all-tests: sniff test analyse
