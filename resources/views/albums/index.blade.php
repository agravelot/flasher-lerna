@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="title">{{ __('My albums') }}</h1>
        <h2 class="subtitle">{{ __('Discover my albums') }}</h2>

        <div id="app">
            <albums-masonry></albums-masonry>
        </div>
    </div>
@endsection
