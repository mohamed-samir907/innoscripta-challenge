.PHONY: up build down logs ps shell db migrate tinker queue-monitor queue-work

up:
	docker compose up -d

build:
	docker compose build

down:
	docker compose down

logs:
	docker logs news_app

ps:
	docker compose ps

shell:
	docker exec -it news_app bash

db:
	docker exec -it news_mysql mysql -u laravel -ppassword

migrate:
	docker exec -it news_app php artisan migrate

tinker:
	docker exec -it news_app php artisan tinker

queue-monitor:
	docker exec -it news_app php artisan queue:monitor default

queue-work:
	docker exec -it news_app php artisan queue:work
