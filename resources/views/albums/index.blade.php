@extends('layouts.app')

@section('pageTitle', __('My albums'))

@section('content')
    <section class="section">
        <div class="container">
{{--            <h1 class="title">{{ __(settings()->get('homepage_title')) }}</h1>--}}
{{--            <h2 class="subtitle">{{ __('Discover my albums') }}</h2>--}}

            @include('albums.partials._index_item', compact('albums'))
        </div>
    </section>

@endsection
