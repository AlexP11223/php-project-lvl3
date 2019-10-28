[![Build Status](https://travis-ci.org/AlexP11223/php-project-lvl3.svg?branch=master)](https://travis-ci.org/AlexP11223/php-project-lvl3)
[![Maintainability](https://api.codeclimate.com/v1/badges/fd2a401ad73e174686e2/maintainability)](https://codeclimate.com/github/AlexP11223/php-project-lvl3/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/fd2a401ad73e174686e2/test_coverage)](https://codeclimate.com/github/AlexP11223/php-project-lvl3/test_coverage)

# Web Page Analyzer

http://alexp11223-web-page-analyzer.herokuapp.com

A simple website created using Lumen framework that extracts some information about the specified web page. The requests are processed using a job queue asynchronously.

![](https://i.imgur.com/V0wBwyR.gif)

## Heroku deployment

1. Add postgres and php buildpack.
2. Add `APP_KEY` (`php artisan key:generate --show`).
3. Enable the worker for queue processing.
