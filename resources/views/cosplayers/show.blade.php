@extends("layouts.app")

@section("content")
    @include('cosplayers.partials._show_item', compact('cosplayer'))
@endsection
