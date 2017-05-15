.PHONY: test composer clean

composer = composer/composer:php5-alpine
phpcli = php:5.6-cli

all: composer test

test: 
	docker run -it --rm -v "${CURDIR}":/usr/src/myapp -w /usr/src/myapp ${phpcli} php ./vendor/phpunit/phpunit/phpunit tests

composer:
	mkdir vendor
	docker run --rm -v ${CURDIR}:/app/vendor -v ${CURDIR}:/app -v ~/.ssh:/root/.ssh $(composer) install

clean: clean-docker-image clean-composer-vendor

clean-docker-image:
	docker rmi $(composer) $(phpcli)

clean-composer-vendor:
	rm -rf vendor/
