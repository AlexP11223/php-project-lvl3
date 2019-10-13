<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class HomeTest extends TestCase
{
    public function testHomePage()
    {
        $this->get('/');
        $this->assertResponseOk();
    }
}
