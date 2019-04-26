@extends('layouts.app')

@section('pageTitle', __('Cosplayers'))

@section('content')
    @include('layouts.partials._messages')
    <div class="container is-centered">
        <h1 class="title">{{ __('Cosplayers') }}</h1>
        <div class="columns is-multiline is-centered">
            @each('cosplayers.partials._cosplayer_item', $cosplayers, 'cosplayer', 'layouts.partials._empty')
        </div>
    </div>
@endsection
