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
}
