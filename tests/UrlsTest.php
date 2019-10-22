<?php

namespace Tests;

use App\Domain;
use App\Url;

class UrlsTest extends TestCase
{
    /**
     * @dataProvider domainProvider
     */
    public function testStore($input, $result)
    {
        $this->post(route('urls.store'), ['url' => $input]);

        if ($result) {
            $this->seeInDatabase('urls', ['address' => $result]);

            $this->get(route('urls.show', ['id' => 1]));
            $this->assertResponseOk();
        } else {
            $this->notSeeInDatabase('urls', ['address' => $result]);

            $this->get(route('urls.show', ['id' => 1]));
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
            ['google.com', 'google.com'],
            ['sub.google.co.uk', 'sub.google.co.uk'],
            ['xn--90adear.xn--p1ai', 'xn--90adear.xn--p1ai'],
            ['8.8.8.8:8080', '8.8.8.8:8080'],
            ['', null],
            ['g', null],
            ['https://', null],
        ];
    }

    public function testIndex()
    {
        factory(Url::class, 10)->create();

        $this->get(route('urls.index'));
        $this->assertResponseOk();
    }
}
