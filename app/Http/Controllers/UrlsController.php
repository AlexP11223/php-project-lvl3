<?php

namespace App\Http\Controllers;

use App\Jobs\AnalysisJob;
use App\Url;
use App\Utils\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UrlsController extends Controller
{
    public function __construct()
    {
    }

    public function show($id, Request $request)
    {
        $url = Url::findOrFail($id);

        if ($request->expectsJson()) {
            return response()->json($url);
        }

        return view('urls.show', ['url' => $url]);
    }

    public function index()
    {
        $urls = Url::orderBy('created_at', 'desc')->paginate();

        return view('urls.index', ['urls' => $urls]);
    }

    public function create()
    {
        return view('urls.create');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        if (isset($input['url'])) {
            $input['url'] = Http::ensureHttp($input['url']); // for url validation rule
        }
        $validator = Validator::make($input, [
            'url' => "required|max:255|url"
        ]);
        if ($validator->fails()) {
            return redirect()->route('urls.create');
        }

        $url = Url::make(['address' => $request->get('url')]);
        $url->state = Url::WAITING;
        $url->save();

        dispatch(new AnalysisJob($url));

        return redirect()->route('urls.show', ['id' => $url->id]);
    }
}
