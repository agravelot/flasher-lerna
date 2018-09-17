@extends('layouts.app')

@section('content')
    <div class="container is-centered">
        <div class="columns is-multiline is-centered">
            @each('albums.partials._index_item', $albums, 'album', 'layouts.partials._empty')
        </div>
    </div>
@endsection
