.PHONY: all test composer clean clean-docker-image clean-composer-vendor

composer = composer/composer:php5-alpine
phpcli = php:5.6-cli

all: composer test

test: composer
	docker run -it --rm -v "${CURDIR}":/usr/src/myapp -w /usr/src/myapp ${phpcli} php ./vendor/phpunit/phpunit/phpunit tests

composer:
	docker run --rm -v ${CURDIR}:/app -v ~/.ssh:/root/.ssh $(composer) install

clean: clean-composer-vendor clean-docker-image

clean-docker-image:
	docker rmi $(composer) $(phpcli)

clean-composer-vendor:
	docker run --rm -v ${PWD}:/app composer/composer:php5-alpine exec "rm -rf vendor"
