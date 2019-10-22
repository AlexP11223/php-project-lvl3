<?php

namespace Tests;

use App\Domain;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DomainsTest extends TestCase
{
    /**
     * @dataProvider domainProvider
     */
    public function testStore($input, $result)
    {
        $this->post(route('domains.store'), ['url' => $input]);

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
            ['http://google.com', 'http://google.com'],
            ['https://google.com/', 'https://google.com/'],
            ['https://google.com/?q=hello', 'https://google.com/?q=hello'],
            ['https://www.google.com', 'https://www.google.com'],
//            ['google.com', 'google.com'],
//            ['sub.google.co.uk', 'sub.google.co.uk'],
//            ['xn--90adear.xn--p1ai', 'xn--90adear.xn--p1ai'],
            ['', null],
            ['g', null],
            ['https://', null],
            ['google', null],
            ['.com', null],
        ];
    }

    public function testIndex()
    {
        factory(Domain::class, 10)->create();

        $this->get(route('domains.index'));
        $this->assertResponseOk();
    }
}
