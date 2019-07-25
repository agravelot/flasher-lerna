@extends('layouts.app')

@section('pageTitle', __('My albums'))

@section('content')
    <div class="hero is-black is-radiusless">
        <div class="hero-body"></div>
    </div>
    <section class="section" style="margin-top: -120px;">
        <div class="container">
            @include('albums.partials._index_item', compact('albums'))
        </div>
    </section>
@endsection
