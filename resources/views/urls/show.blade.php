@extends('layouts.page')

@section('content')
    <h3><a href="{{ $url->normalizedAddress() }}" rel="nofollow" target="_blank">{{ $url->address }}</a> ({{ $url->created_at }})</h3>

    @switch ($url->state)
        @case (\App\Url::WAITING)
        @case (\App\Url::PROCESSING)
            <span>Processing...</span>
            <div>
                <img src="{{ url('img/loading.gif') }}" alt="loading indicator" style="margin-left: -50px"/>
            </div>
            @break
        @case (\App\Url::FAILED)
            <span class="error">Failed.</span>
            <div>Check the address and <a href="{{ route('urls.create') }}">try again</a>.</div>
            @break
        @case (\App\Url::SUCCEEDED)
            <table class="table url-info-table">
                <tr>
                    <th scope="row">Status</th>
                    <td>{{ $url->statusCode }}</td>
                </tr>
                <tr>
                    <th scope="row">Content-Length</th>
                    <td>{{ $url->contentLength }}</td>
                </tr>
            </table>
            @break
    @endswitch

@endsection

