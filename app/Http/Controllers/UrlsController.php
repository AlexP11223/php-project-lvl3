<?php

namespace App\Http\Controllers;

use App\Jobs\AnalysisJob;
use App\Url;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UrlsController extends Controller
{
    public function __construct()
    {
    }

    public function show($id, Request $request)
    {
        $url = Url::find($id);

        if (!$url) {
            abort(404);
        }

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
            $input['url'] = Url::ensureHttp($input['url']); // for url validation rule
        }
        $validator = Validator::make($input, [
            'url' => "required|max:255|url"
        ]);
        if ($validator->fails()) {
            return redirect()->route('urls.create');
        }

        $url = Url::create(['address' => $request->get('url')]);
        $url->setState(Url::WAITING);

        dispatch(new AnalysisJob($url));

        return redirect()->route('urls.show', ['id' => $url->id]);
    }
}
