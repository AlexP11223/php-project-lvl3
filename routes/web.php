<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', ['as' => 'urls.create', 'uses' => 'UrlsController@create']);
$router->post('/urls', ['as' => 'urls.store', 'uses' => 'UrlsController@store']);
$router->get('/urls/{id}', ['as' => 'urls.show', 'uses' => 'UrlsController@show']);
$router->get('/urls', ['as' => 'urls.index', 'uses' => 'UrlsController@index']);
