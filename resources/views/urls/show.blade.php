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
            <script>
				function checkState(retryCount) {
					fetch('{{ route('urls.show', ['id' => $url->id]) }}', {headers: {'accept': 'application/json'}})
						.then(function (response) {
							return response.json()
						}).then(function (url) {
                            if (!['{{ \App\Url::WAITING }}', '{{ \App\Url::PROCESSING }}'].includes(url.state)) {
                                window.location.reload();
                            } else if (retryCount > 0) {
                                setTimeout(() => checkState(retryCount- 1), 600);
                            }
					});
				}

				checkState(100);
            </script>
            @break
        @case (\App\Url::FAILED)
            <span class="error">Failed.</span>
            <div>Check the address and <a href="{{ route('urls.create') }}">try again</a>.</div>
            @break
        @case (\App\Url::SUCCEEDED)
            <table class="table url-info-table">
                <tr>
                    <th scope="row">Status</th>
                    <td class="@if ($url->statusCode >= 400) http-error @endif">{{ $url->statusCode }} {{ $url->getStatusCodeDescription() }}</td>
                </tr>
                @if ($url->contentLength)
                    <tr>
                        <th scope="row">Content-Length</th>
                        <td>{{ $url->contentLength }}</td>
                    </tr>
                @endif
            </table>
            @break
    @endswitch

@endsection

