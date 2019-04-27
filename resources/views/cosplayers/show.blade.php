@extends("layouts.app")

@section('pageTitle', $cosplayer->name)

@section("content")
    @include('cosplayers.partials._show_item', compact('cosplayer', 'albums'))
@endsection
