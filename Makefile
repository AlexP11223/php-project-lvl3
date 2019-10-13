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
	tail -f `ls -t storage/logs/lumen* | head -1`
