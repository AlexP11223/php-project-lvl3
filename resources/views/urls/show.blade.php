@extends('layouts.page')

@section('content')
    <h3>{{ $url->address }} ({{ $url->created_at }})</h3>

    <table class="table table-bordered">
        <tr>
            <th style="width: 30%" scope="row">Status</th>
            <td>{{ $url->statusCode }}</td>
        </tr>
        <tr>
            <th scope="row">Content-Length</th>
            <td>{{ $url->contentLength }}</td>
        </tr>
    </table>
@endsection

