PHP=php
CURRENT_DIR=$(shell pwd)
ifdef VERSION
    PHP=docker run -it --rm --name phpcli -v $(CURRENT_DIR):/usr/src/myapp -w /usr/src/myapp php:$(VERSION)-cli php
endif
PORT?=8000
HOST?=127.0.0.1
COM_COLOR   = \033[0;34m
OBJ_COLOR   = \033[0;36m
OK_COLOR    = \033[0;32m
ERROR_COLOR = \033[0;31m
WARN_COLOR  = \033[0;33m
NO_COLOR    = \033[m

.PHONY: test yarn.lock server cache-clear install composer.lock vendor help
.DEFAULT_GOAL= help

help:
    @grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-10s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


yarn.lock: package.json
	yarn upgrade

node_modules: yarn.lock
	yarn install

composer.lock: composer.json
	composer update

vendor: composer.lock
	composer install

install: vendor node_modules

server: install ## Lance le serveur interne de PHP
	killall php
	$(PHP) artisan serve &

watch: server
	killall node
	yarn run watch &

test: install ## Lance les tests unitaire
	$(PHP) ./vendor/bin/phpunit --stop-on-failure