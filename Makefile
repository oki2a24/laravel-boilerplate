up:
	docker-compose up -d
app:
	docker-compose exec --user=app app bash
stop:
	docker-compose stop
php-ide-helper:
	docker-compose exec --user=app app php artisan ide-helper:models --write --reset
