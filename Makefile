install:
	composer install

setup: install
	cp -n .env.example .env || true
	php artisan key:generate
	touch storage/db.sqlite
	php artisan migrate

db-reset:
	php artisan migrate:fresh

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
