<?php

namespace Tests;

use App\Jobs\AnalysisJob;
use App\Url;
use BlastCloud\Guzzler\UsesGuzzler;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class AnalysisJobTest extends TestCase
{
    /**
     * @var Url
     */
    private $url;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = factory(Url::class, 1)->create()[0];
    }

    public function testMissingContentLength()
    {
        $this->guzzler->queueResponse(new Response(200, [], 'hello!'));
        (new AnalysisJob($this->url))->handle();

        $this->url->refresh();
        self::assertEquals(Url::SUCCEEDED, $this->url->state);
        self::assertEquals(200, $this->url->statusCode);
        self::assertEquals('hello!', $this->url->body);
        self::assertNull($this->url->contentLength);
    }
}
