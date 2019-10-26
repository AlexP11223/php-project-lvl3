<?php

namespace Tests;

use App\Jobs\AnalysisJob;
use App\Url;
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

    public function testNormal()
    {
        $this->guzzler->queueResponse(new Response(200, ['content-length' => 6], 'hello!'));
        (new AnalysisJob($this->url))->handle();

        $this->url->refresh();
        self::assertEquals(Url::SUCCEEDED, $this->url->state);
        self::assertEquals(200, $this->url->statusCode);
        self::assertEquals('hello!', $this->url->body);
        self::assertEquals(6, $this->url->contentLength);
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

    /**
     * @dataProvider httpErrorProvider
     */
    public function testHttpError($statusCode)
    {
        $this->guzzler->queueResponse(new Response($statusCode));
        (new AnalysisJob($this->url))->handle();

        $this->url->refresh();
        self::assertEquals(Url::SUCCEEDED, $this->url->state);
        self::assertEquals($statusCode, $this->url->statusCode);
        self::assertNull($this->url->body);
        self::assertNull($this->url->contentLength);
    }

    public function httpErrorProvider()
    {
        return [
            [404],
            [500],
        ];
    }
}
