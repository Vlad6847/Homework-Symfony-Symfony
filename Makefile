start: stop
	@docker-compose -f docker-compose.yml up -d 
	@docker-compose exec php-fpm bash

stop:
	@docker-compose stop

php:
	@docker-compose exec php-fpm bash
kill:
	@docker kill $$(docker ps -q)
build: stop
	@docker-compose -f docker-compose.yaml up -d --build --no-cache
