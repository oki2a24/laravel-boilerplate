up:
	docker-compose up -d
app:
	docker-compose exec --user=app app bash
stop:
	docker-compose stop

php-check-all: php-ide-helper php-pint php-stan php-test
php-ide-helper:
	docker-compose exec --user=app app php artisan ide-helper:models --write --reset
php-pint:
	docker-compose exec --user=app app ./vendor/bin/pint -v
php-stan:
	docker-compose exec --user=app app ./vendor/bin/phpstan analyse
php-test:
	docker-compose exec --user=app app php artisan test
