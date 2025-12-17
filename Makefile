APP      := vc19
PID_FILE := .go.pid

# ── Defaults for local `make run` ─────────────────────────────────────────────
# Create a .env file to override (see .env.example).
PORT           ?= 8080
SESSION_KEY    ?= dev-session-key-change-in-production-32b
ADMIN_USERNAME ?= admin
ADMIN_PASSWORD ?= admin

-include .env
export

.PHONY: up down logs build run stop restart clean lint test help

# ── Docker ────────────────────────────────────────────────────────────────────

up: ## Start app in Docker and tail logs
	docker compose up --build -d
	@echo ""
	@echo "  App  →  http://localhost:8080"
	@echo ""
	docker compose logs -f app

down: ## Stop and remove Docker containers
	docker compose down

logs: ## Tail app logs from Docker
	docker compose logs -f app

# ── Go binary (local) ─────────────────────────────────────────────────────────

build: ## Compile the Go binary
	go build -o $(APP) .

run: stop clean build ## Stop, clean, rebuild, and start in the background
	@./$(APP) & echo $$! > $(PID_FILE)
	@echo "App started  →  http://localhost:$(PORT)  (PID $$(cat $(PID_FILE)))"

stop: ## Stop the background Go app
	@if [ -f $(PID_FILE) ]; then \
		kill $$(cat $(PID_FILE)) 2>/dev/null || true; \
		rm -f $(PID_FILE); \
		echo "App stopped."; \
	else \
		echo "No running app ($(PID_FILE) not found)."; \
	fi

restart: stop run ## Restart the background Go app

clean: ## Remove the compiled binary and PID file
	rm -f $(APP) $(APP).exe $(PID_FILE)

lint: ## Run go vet
	go vet ./...

test: ## Run tests
	go test ./...

# ── Help ──────────────────────────────────────────────────────────────────────

help: ## Show this help
	@echo ""
	@echo "Usage: make <target>"
	@echo ""
	@printf "Docker:\n"
	@grep -E '^(up|down|logs):.*?## .*$$' $(MAKEFILE_LIST) | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2}'
	@echo ""
	@printf "Go (local):\n"
	@grep -E '^(build|run|stop|restart|clean|lint|test):.*?## .*$$' $(MAKEFILE_LIST) | \
		awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[36m%-12s\033[0m %s\n", $$1, $$2}'
	@echo ""

.DEFAULT_GOAL := help
