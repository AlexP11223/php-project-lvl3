<?php

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\InteractsWithExceptionHandling;
use Laravel\Lumen\Testing\DatabaseMigrations;

abstract class TestCase extends \Laravel\Lumen\Testing\TestCase
{
    use DatabaseMigrations;
    use InteractsWithExceptionHandling;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling(); // show exceptions in output
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
}
