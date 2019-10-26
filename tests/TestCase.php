<?php

namespace Tests;

use BlastCloud\Guzzler\UsesGuzzler;
use GuzzleHttp\Client;
use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Laravel\Lumen\Testing\DatabaseMigrations;

abstract class TestCase extends \Laravel\Lumen\Testing\TestCase
{
    use DatabaseMigrations;
    use InteractsWithExceptionHandling;
    use UsesGuzzler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling(); // show exceptions in output

        $this->app->instance(Client::class, $this->guzzler->getClient());
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    protected static function getFixtureFilePath($relativeFilePath)
    {
        return dirname(__FILE__) . '/fixtures/' . $relativeFilePath;
    }
}
