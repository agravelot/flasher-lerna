@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="title">{{ __('My albums') }}</h1>
        <div class="columns is-multiline">
            @each('albums.partials._index_item', $albums, 'album', 'layouts.partials._empty')
        </div>
        {{ $albums->links() }}
    </div>
@endsection
