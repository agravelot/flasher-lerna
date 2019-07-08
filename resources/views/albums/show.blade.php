@extends("layouts.app")

@section('pageTitle', $album->title)

@section("content")
    <section class="section">
        <div class="container">
            @include('albums.partials._show_item', compact('albumResource', 'album'))
        </div>
    </section>
@endsection
