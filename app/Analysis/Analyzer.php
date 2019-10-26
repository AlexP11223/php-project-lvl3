<?php

namespace App\Analysis;

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
            ]
        );

        $contentLengths = $response->getHeader('content-length');

        return [
            'statusCode' => $response->getStatusCode(),
            'body' => $response->getBody()->getContents(),
            'contentLength' => $contentLengths ? $contentLengths[0] : null,
        ];
    }
}
