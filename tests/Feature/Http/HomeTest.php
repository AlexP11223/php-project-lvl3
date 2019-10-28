<?php

namespace Tests\Feature\Http;

use Tests\TestCase;

class HomeTest extends TestCase
{
    public function testHomePage()
    {
        $this->get('/');
        $this->assertResponseOk();
    }
}
