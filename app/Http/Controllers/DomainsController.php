<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DomainsController extends Controller
{
    public function __construct()
    {
    }

    public function show($id)
    {
        $domain = DB::table('domains')->find($id);

        if (!$domain) {
            abort(404);
        }

        return view('domains.show', ['domain' => $domain]);
    }

    public function index()
    {
        $domains = DB::table('domains')->paginate();

        return view('domains.index', ['domains' => $domains]);
    }

    public function create()
    {
        return view('domains.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => "required|max:255|url"
        ]);
        if ($validator->fails()) {
            return redirect()->route('domains.create');
        }

        $url = $request->get('url');

        $id = DB::table('domains')->insertGetId(
            ['name' => $url]
        );

        return redirect()->route('domains.show', ['id' => $id]);
    }
}
