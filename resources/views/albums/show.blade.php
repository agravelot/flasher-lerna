@extends("layouts.app")

@section('pageTitle', $album->resource->title)

@section("content")
    <section class="section">
        <div class="container">
            @include('albums.partials._show_item', compact('album'))
        </div>
    </section>
@endsection
