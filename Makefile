install:
	docker-compose up -d
	docker-compose exec -w /code/app php-fpm-parser composer install
	docker-compose exec -w /code/app php-fpm-parser npm install
front_dev:
	docker-compose exec -w /code/app php-fpm-parser npm run dev-server

front_build:
	docker-compose exec -w /code/app php-fpm-parser npm run build

stop:
	docker-compose down	
	
start:
	docker-compose up -d
	docker-compose exec -w /code/app php-fpm-parser php bin/console messenger:consume async

	