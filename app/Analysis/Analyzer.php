<?php

namespace App\Analysis;

use DiDom\Document;
use GuzzleHttp\Client;

class Analyzer
{
    /**
     * @var Client
     */
    private $httpClient;

    public function __construct()
    {
        $this->httpClient = app(Client::class);
    }

    public function requestData(string $url)
    {
        $response = $this->httpClient->get(
            $url,
            [
                'timeout' => 20,
                'connect_timeout' => 15,
                'http_errors' => false,
            ]
        );

        $body = $response->getBody()->getContents();
        $contentLengths = $response->getHeader('content-length');

        return [
            'statusCode' => $response->getStatusCode(),
            'body' => $body ?: null,
            'contentLength' => $contentLengths ? $contentLengths[0] : null,
        ];
    }

    public function extractPageInfo(string $html)
    {
        $doc = new Document($html);

        $metaDescription = $doc->find('meta[name="description"]');
        $metaKeywords = $doc->find('meta[name="keywords"]');
        $h1 = $doc->find('h1');

        return [
            'description' => $metaDescription ? $metaDescription[0]->attr('content') : null,
            'keywords' => $metaKeywords ? $metaKeywords[0]->attr('content') : null,
            'heading' => $h1 ? $h1[0]->text() : null,
        ];
    }
}
