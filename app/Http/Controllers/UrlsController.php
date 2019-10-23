<?php

namespace App\Http\Controllers;

use App\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UrlsController extends Controller
{
    public function __construct()
    {
    }

    public function show($id)
    {
        $domain = Url::find($id);

        if (!$domain) {
            abort(404);
        }

        return view('urls.show', ['url' => $domain]);
    }

    public function index()
    {
        $urls = Url::paginate();

        return view('urls.index', ['urls' => $urls]);
    }

    public function create()
    {
        return view('urls.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        if (isset($input['url']) && strpos($input['url'], 'http') !== 0) {
            $input['url'] = "http://${input['url']}"; // for url validation rule
        }
        $validator = Validator::make($input, [
            'url' => "required|max:255|url"
        ]);
        if ($validator->fails()) {
            return redirect()->route('urls.create');
        }

        $url = Url::create(['address' => $request->get('url')]);

        return redirect()->route('urls.show', ['id' => $url->id]);
    }
}
