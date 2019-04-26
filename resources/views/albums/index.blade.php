@extends('layouts.app')

@section('pageTitle', __('My albums'))

@section('content')
    <div class="container">
        <h1 class="title">{{ __('My albums') }}</h1>
        <h2 class="subtitle">{{ __('Discover my albums') }}</h2>

        <div id="app">
            <albums-masonry :data="{{ $albums->response()->getContent() }}"></albums-masonry>
        </div>
    </div>
@endsection
