<?php

namespace App;

use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    public const WAITING = 'waiting';
    public const PROCESSING = 'processing';
    public const SUCCEEDED = 'succeeded';
    public const FAILED = 'failed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address',
        'statusCode',
        'contentLength',
        'body',
        'description',
        'keywords',
        'heading',
    ];

    /**
     * The attributes that should be hidden for arrays and JSON.
     *
     * @var array
     */
    protected $hidden = ['body'];

    public static function ensureHttp(string $address)
    {
        if (stripos($address, 'http://') !== 0 && strpos($address, 'https://') !== 0) {
            return "http://$address";
        }
        return $address;
    }

    public function normalizedAddress()
    {
        return self::ensureHttp($this->address);
    }

    public function setState($state)
    {
        $this->state = $state;
        $this->save();
    }

    public function getStatusCodeDescription()
    {
        return (new Response($this->statusCode))->getReasonPhrase();
    }
}
