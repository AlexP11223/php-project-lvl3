<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DomainsTest extends TestCase
{
    /**
     * @dataProvider domainProvider
     */
    public function testStore($input, $result)
    {
        $this->post(route('domains.store'), ['domain' => $input]);

        if ($result) {
            $this->seeInDatabase('domains', ['name' => $result]);

            $this->get(route('domains.show', ['id' => 1]));
            $this->assertResponseOk();
        } else {
            $this->notSeeInDatabase('domains', ['name' => $result]);

            $this->get(route('domains.show', ['id' => 1]));
            $this->assertResponseStatus(404);
        }
    }

    public function domainProvider()
    {
        return [
            ['google.com', 'google.com'],
            ['http://google.com', 'google.com'],
            ['https://google.com', 'google.com'],
            ['https://google.com/', 'google.com'],
            ['https://google.com/?q=hello', 'google.com'],
            ['https://google.com/?q=hello&foo=google.ru', 'google.com'],
            ['https://www.google.com', 'www.google.com'],
            ['google.co.uk', 'google.co.uk'],
            ['sub.google.com', 'sub.google.com'],
            ['xn--90adear.xn--p1ai/', 'xn--90adear.xn--p1ai'],
            ['', null],
            ['g', null],
            ['https://', null],
            ['google', null],
            ['.com', null],
        ];
    }
}
