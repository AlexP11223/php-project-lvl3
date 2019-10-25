<?php

namespace Tests;

use App\Jobs\AnalysisJob;
use App\Url;
use BlastCloud\Guzzler\UsesGuzzler;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class UrlsTest extends TestCase
{
    use UsesGuzzler;

    /**
     * @dataProvider urlProvider
     */
    public function testStore($input, $resultUrl)
    {
        $this->app->instance(Client::class, $this->guzzler->getClient());
        $this->guzzler->queueResponse(new Response(200, ['content-length' => 6], 'hello!'));

        if ($resultUrl) {
            $this->expectsJobs(AnalysisJob::class);
        }

        $this->post(route('urls.store'), ['url' => $input]);

        if ($resultUrl) {
            $this->seeInDatabase('urls', [
                'address' => $resultUrl,
                'state' => Url::WAITING
            ]);

            $this->get(route('urls.show', ['id' => 1]));
            $this->assertResponseOk();

            $this->json('GET', route('urls.show', ['id' => 1]))
                ->seeJsonContains([
                    'state' => Url::WAITING
                ]);
        } else {
            $this->notSeeInDatabase('urls', ['address' => $resultUrl]);

            $this->withExceptionHandling();
            $this->get(route('urls.show', ['id' => 1]));
            $this->assertResponseStatus(404);
        }
    }

    public function urlProvider()
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
            ['        http://google.com ', 'http://google.com'],
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
