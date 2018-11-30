@extends("layouts.app")

@section("content")
    @include('cosplayers.partials._show_item', ['cosplayer' => $cosplayer])
@endsection
