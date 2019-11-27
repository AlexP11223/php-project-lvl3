[![Build Status](https://travis-ci.org/AlexP11223/php-project-lvl3.svg?branch=master)](https://travis-ci.org/AlexP11223/php-project-lvl3)
[![Maintainability](https://api.codeclimate.com/v1/badges/fd2a401ad73e174686e2/maintainability)](https://codeclimate.com/github/AlexP11223/php-project-lvl3/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/fd2a401ad73e174686e2/test_coverage)](https://codeclimate.com/github/AlexP11223/php-project-lvl3/test_coverage)

# Web Page Analyzer

http://alexp11223-web-page-analyzer.herokuapp.com

A simple website created using Lumen framework that extracts some information about the specified web page. The requests are processed using a job queue asynchronously.

![](https://i.imgur.com/V0wBwyR.gif)

## Development setup

1. Run `make setup` to generate `.env` file, create SQLite database, apply migrations.
2. Run `make run` to launch web server (http://localhost:8000).
3. Run `make queue-daemon` or `make empty-queue` to process the job queue.
4. Run `make lint test` to run linter and tests.

See [Makefile](/Makefile) for other useful commands. Check out https://makefile.site if you are interested in this approach.

## Heroku deployment

1. Add postgres and php buildpack.
2. Add `APP_KEY` (`php artisan key:generate --show`).
3. Enable the worker for queue processing.
