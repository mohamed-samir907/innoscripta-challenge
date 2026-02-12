.PHONY: up build down logs ps shell

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
