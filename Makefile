.PHONY: all test cs-check cs-fix composer clean clean-docker-image clean-composer-vendor

composer = composer:2
phpcli = php:8

all: composer test

test: composer
	docker run -it --rm -v "${CURDIR}":/usr/src/myapp -w /usr/src/myapp ${phpcli} php ./vendor/bin/phpunit tests

cs-check: composer
	docker run -it --rm -v "${CURDIR}":/usr/src/myapp -w /usr/src/myapp ${phpcli} php ./vendor/bin/phpcs --standard=PSR12 src tests

cs-fix: composer
	docker run -it --rm -v "${CURDIR}":/usr/src/myapp -w /usr/src/myapp ${phpcli} php ./vendor/bin/phpcbf --standard=PSR12 src tests

composer:
	docker run --rm -v ${CURDIR}:/app -v ~/.ssh:/root/.ssh $(composer) install

composer-update: composer
	docker run --rm -v ${CURDIR}:/app -v ~/.ssh:/root/.ssh $(composer) update

clean: clean-composer-vendor clean-docker-image

clean-docker-image:
	docker rmi $(composer) $(phpcli)

clean-composer-vendor:
	docker run --rm -v ${PWD}:/app ${composer} exec "rm -rf vendor"
