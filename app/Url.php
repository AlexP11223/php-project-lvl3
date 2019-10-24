<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
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
    ];

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
}
