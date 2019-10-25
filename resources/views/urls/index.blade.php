@extends('layouts.page')

@section('content')
    <ul class="url-list">
        @foreach($urls as $url)
            <li>
                <a href="{{ route('urls.show', ['id' => $url->id]) }}">{{ $url->address }} ({{ $url->created_at }})</a>
                @switch ($url->state)
                    @case (\App\Url::WAITING)
                    @case (\App\Url::PROCESSING)
                        <i class="fas fa-spinner"></i>
                        @break
                    @case (\App\Url::FAILED)
                        <i class="fas fa-exclamation" style="color: red"></i>
                        @break
                @endswitch
            </li>
        @endforeach
    </ul>

    {{ $urls->links() }}
@endsection

