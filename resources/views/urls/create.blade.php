@extends('layouts.page')

@section('content')
    <div class="jumbotron col-md-8 mx-auto">
        <form method="POST" action="{{ route('urls.store') }}">
            <div class="input-group input-group-lg mb-2">
                <input type="text" required maxlength="255" class="form-control" name="url" placeholder="hexlet.io">
            </div>
            <button type="submit" class="btn btn-primary btn-lg">Analyze</button>
        </form>
    </div>
@endsection

