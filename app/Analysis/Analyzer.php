<?php

namespace App\Analysis;

use GuzzleHttp\Client;

class Analyzer
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(string $url)
    {
        $this->url = $url;
        $this->httpClient = app(Client::class);
    }

    public function getResults()
    {
        $response = $this->httpClient->get(
            $this->url,
            [
                'timeout' => 20,
                'connect_timeout' => 15,
            ]
        );

        return [
            'statusCode' => $response->getStatusCode(),
            'body' => $response->getBody()->getContents(),
            'contentLength' => $response->getHeader('content-length')[0]
        ];
    }
}
