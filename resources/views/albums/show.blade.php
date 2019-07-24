@extends('layouts.app')

@section('pageTitle', $album->title)

@section('content')
    <div class="hero is-black is-radiusless">
        <div class="hero-body"></div>
    </div>
    <section class="section">
        <div class="container">
            @include('albums.partials._show_item', compact('album'))
        </div>
    </section>
@endsection

@include('layouts.partials._og_tags', ['model' => $album])
