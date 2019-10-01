@extends('layouts.app')

@section('pageTitle', $cosplayer->name)

@section('content')
    @include('cosplayers.partials._show_item', compact('cosplayer', 'albums'))
@endsection

@include('layouts.partials._og_tags', ['openGraph' => new App\Http\OpenGraphs\CosplayerOpenGraph($cosplayer)])
