@extends('layouts.app')

@section('pageTitle', __('My albums'))

@section('content')
    @if ($albums->isNotEmpty())
        <div class="hero is-black is-radiusless">
            <div class="hero-body"></div>
        </div>
    @endif

    <section class="section" style="margin-top: {{ $albums->isEmpty() ? '0' : '-120' }}px;">
        <div class="container">
            @include('albums.partials._index_item', compact('albums'))
        </div>
    </section>
@endsection
