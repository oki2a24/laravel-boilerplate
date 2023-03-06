up:
	docker-compose up -d
app:
	docker-compose exec --user=app app bash
stop:
	docker-compose stop
