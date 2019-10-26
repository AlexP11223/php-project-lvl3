<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Url;

$factory->define(Url::class, function (Faker\Generator $faker) {
    return [
        'address' => $faker->url,
        'state' => Url::WAITING,
    ];
});

$factory->defineAs(Url::class, 'waiting', function (Faker\Generator $faker) use ($factory) {
    return $factory->raw(Url::class);
});

$factory->defineAs(Url::class, 'processing', function (Faker\Generator $faker) use ($factory) {
    return array_merge($factory->raw(Url::class), [
        'state' => Url::PROCESSING,
    ]);
});

$factory->defineAs(Url::class, 'succeeded', function (Faker\Generator $faker) use ($factory) {
    return array_merge($factory->raw(Url::class), [
        'state' => Url::SUCCEEDED,
        'statusCode' => $faker->shuffleArray([200, 404, 418, 500])[0],
        'body' => $faker->randomHtml(),
        'heading' => $faker->text(),
        'keywords' => $faker->text(),
        'description' => $faker->text(),
    ]);
});

$factory->defineAs(Url::class, 'failed', function (Faker\Generator $faker) use ($factory) {
    return array_merge($factory->raw(Url::class), [
        'state' => Url::FAILED,
    ]);
});
