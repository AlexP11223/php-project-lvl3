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
        $domainExtractionRegex = '/(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z0-9][a-z0-9-]{0,61}[a-z0-9]/';

        $validator = Validator::make($request->all(), [
            'domain' => "required|max:255|regex:$domainExtractionRegex"
        ]);

        if ($validator->fails()) {
            return redirect()->route('domains.create');
        }

        preg_match($domainExtractionRegex, $request->get('domain'), $matches);
        $domain = $matches[0];

        $id = DB::table('domains')->insertGetId(
            ['name' => $domain]
        );

        return redirect()->route('domains.show', ['id' => $id]);
    }
}
