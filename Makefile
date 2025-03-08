bash:
	docker compose run shipmonk-packing-app bash

build:
	docker compose up -d --remove-orphans --build

start:
	docker compose up -d --remove-orphans

stop:
	docker compose stop

# XDEBUG_MODE=off composer stan
# XDEBUG_MODE=off bin/package-generator
