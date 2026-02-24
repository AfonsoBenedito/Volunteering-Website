run-web: clean-web
	docker compose up --build

stop-web:
	docker compose down

clean-web:
	docker compose down -v
