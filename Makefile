start: stop
	@docker-compose -f docker-compose.yml up -d

stop:
	@docker-compose stop

php:
	@docker-compose exec php-fpm bash

