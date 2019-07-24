@extends('layouts.app')

@section('pageTitle', $category->name)

@section('content')
    <div class="hero is-black is-radiusless">
        <div class="hero-body"></div>
    </div>
    <section class="section" style="margin-top: -120px;">
        <div class="container is-centered">

            @if ($category->description)
                <p class="has-text-justified has-margin-bottom-md">{!! $category->description !!}</p>
            @endif

            @php
                /** @var \App\Models\Category $category */
                $albums = $category->load('publishedAlbums.media')->publishedAlbums()->paginate();
            @endphp
            @include('albums.partials._index_item', compact('albums'))
        </div>
    </section>
@endsection
