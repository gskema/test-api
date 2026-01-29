init:
	docker-compose build
	docker-compose up -d
	@make install

start:
	docker-compose start

stop:
	docker-compose stop

destroy:
	docker-compose down -v --rmi all

restart: stop start

recreate:
	docker-compose up --build --force-recreate
	@make install

shell:
	docker-compose exec php /bin/bash

install:
	docker-compose exec php composer install

fixture:
	docker-compose exec php composer fixture

cache:
	docker-compose exec php composer cache

style:
	docker-compose exec php composer style

test:
	docker-compose exec php composer test
