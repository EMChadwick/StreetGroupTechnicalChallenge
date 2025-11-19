.PHONY: up down restart shell artisan migrate

# Start Sail containers
up:
	./vendor/bin/sail up -d

# Stop Sail containers
down:
	./vendor/bin/sail down

# Restart Sail containers
restart: down up

# Open a shell in Sail container
shell:
	./vendor/bin/sail shell

# Run artisan commands in Sail container
artisan:
	./vendor/bin/sail artisan $(filter-out $@,$(MAKECMDGOALS))

# Run migrations
migrate:
	./vendor/bin/sail artisan migrate

# Tail Laravel logs
logs:
	./vendor/bin/sail logs -f

test:
	./vendor/bin/sail phpunit