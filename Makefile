install:
	composer install

lint:
	composer phpcs

fix-lint:
	composer phpcbf

test:
	composer phpunit

run:
	php -S localhost:8000 -t public

logs:
	tail -f storage/logs/lumen.log
