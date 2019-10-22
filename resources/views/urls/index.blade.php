@extends('layouts.page')

@section('content')
    <ul>
        @foreach($urls as $url)
            <li><a href="{{ route('urls.show', ['id' => $url->id]) }}">{{ $url->address }}</a></li>
        @endforeach
    </ul>

    {{ $urls->links() }}
@endsection

