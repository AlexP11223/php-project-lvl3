<?php

namespace Tests\Feature\Jobs;

use App\Jobs\AnalysisJob;
use App\Url;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class AnalysisJobTest extends TestCase
{
    /**
     * @var Url
     */
    private $url;

    protected function setUp(): void
    {
        parent::setUp();

        $this->url = factory(Url::class)->create();
    }

    public function testNormal()
    {
        $html = file_get_contents(self::getFixtureFilePath('basic.html'));
        $this->guzzler->queueResponse(new Response(200, ['content-length' => strlen($html)], $html));
        (new AnalysisJob($this->url))->handle();

        $this->url->refresh();
        self::assertEquals(Url::SUCCEEDED, $this->url->state);
        self::assertEquals(200, $this->url->statusCode);
        self::assertEquals($html, $this->url->body);
        self::assertEquals(strlen($html), $this->url->contentLength);
        self::assertEquals('hello world description', $this->url->description);
        self::assertEquals('hello,world', $this->url->keywords);
        self::assertEquals('Hello world', $this->url->heading);
    }

    public function testNoHtml()
    {
        $this->guzzler->queueResponse(new Response(200, ['content-length' => 6], 'hello!'));
        (new AnalysisJob($this->url))->handle();

        $this->url->refresh();
        self::assertEquals(Url::SUCCEEDED, $this->url->state);
        self::assertEquals(200, $this->url->statusCode);
        self::assertEquals('hello!', $this->url->body);
        self::assertEquals(6, $this->url->contentLength);
        self::assertNull($this->url->description);
        self::assertNull($this->url->keywords);
        self::assertNull($this->url->heading);
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

    public function testFailedRequest()
    {
        // will throw because no queued response
        $analyzer = new AnalysisJob($this->url);
        $analyzer->outputErrorsToConsole = false;
        $analyzer->handle();

        $this->url->refresh();
        self::assertEquals(Url::FAILED, $this->url->state);
        self::assertNull($this->url->statusCode);
        self::assertNull($this->url->body);
        self::assertNull($this->url->contentLength);
    }
}
