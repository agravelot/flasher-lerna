@extends("layouts.app")

@section('pageTitle', $album->resource->title)

@section("content")
    @include('albums.partials._show_item', compact('album'))
@endsection
