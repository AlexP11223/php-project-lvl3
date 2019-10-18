<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

$router->get('/', ['as' => 'home', function () {
    return view('domains.create');
}]);

$router->post('/domains', ['as' => 'domains.store', function (Request $request) {
    $domainExtractionRegex = '/(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]/';

    $validator = Validator::make($request->all(), [
        'domain' => "required|max:255|regex:$domainExtractionRegex"
    ]);

    if ($validator->fails()) {
        return redirect()->route('home');
    }

    preg_match($domainExtractionRegex, $request->get('domain'), $matches);
    $domain = $matches[0];

    $id = DB::table('domains')->insertGetId(
        ['name' => $domain]
    );

    return redirect()->route('domains.show', ['id' => $id]);
}]);

$router->get('/domains/{id}', ['as' => 'domains.show', function ($id) {
    $domain = DB::table('domains')->find($id);

    if (!$domain) {
        abort(404);
    }

    return view('domains.show', ['domain' => $domain]);
}]);
