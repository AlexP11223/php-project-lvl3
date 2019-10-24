@extends('layouts.page')

@section('content')
    <h3><a href="{{ $url->normalizedAddress() }}" rel="nofollow" target="_blank">{{ $url->address }}</a> ({{ $url->created_at }})</h3>

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
@endsection

