@extends('layouts.app')

@section('content')
    @include('layouts.partials._messages')
    <div class="container is-centered">
        <div class="columns is-multiline is-centered">
            @each('cosplayers.partials._cosplayer_item', $cosplayers, 'cosplayer', 'layouts.partials._empty')
        </div>
    </div>
@endsection
