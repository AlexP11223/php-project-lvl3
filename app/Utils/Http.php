<?php

namespace App\Utils;

use GuzzleHttp\Psr7\Response;

class Http
{
    public static function ensureHttp(string $address)
    {
        if (stripos($address, 'http://') !== 0 && strpos($address, 'https://') !== 0) {
            return "http://$address";
        }
        return $address;
    }

    public static function getStatusCodeDescription($statusCode)
    {
        return (new Response($statusCode))->getReasonPhrase();
    }
}
