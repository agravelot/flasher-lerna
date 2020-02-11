@extends('layouts.app')

@section('pageTitle', $album->title)

@section('seo_description', (new App\Http\OpenGraphs\AlbumOpenGraph($album))->description())

@section('content')
    <div class="hero is-black is-radiusless">
        <div class="hero-body"></div>
    </div>
    <section class="section">
        <div class="container">
            @include('albums.partials._show_item', compact('album'))
        </div>
        {!! new \App\Http\SchemasOrg\AlbumSchema($album) !!}
    </section>
    @include('layouts.partials._return_to_top')
@endsection

@include('layouts.partials._og_tags', ['openGraph' => new App\Http\OpenGraphs\AlbumOpenGraph($album)])
