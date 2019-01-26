@extends("layouts.app")

@section("content")
    @include('albums.partials._show_item', compact('album'))
@endsection
