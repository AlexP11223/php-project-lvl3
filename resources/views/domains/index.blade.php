@extends('layouts.page')

@section('content')
    <ul>
        @foreach($domains as $domain)
            <li><a href="{{ route('domains.show', ['id' => $domain->id]) }}">{{ $domain->name }}</a></li>
        @endforeach
    </ul>

    {{ $domains->links() }}
@endsection

