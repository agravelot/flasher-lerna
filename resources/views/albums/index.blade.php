@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="title">{{ __('My albums') }}</h1>
        <h2 class="subtitle">{{ __('Discover my albums') }}</h2>
        
        <div class="masonry">
            @each('albums.partials._index_item', $albums, 'album', 'layouts.partials._empty')
        </div>

        {{ $albums->links() }}
    </div>
@endsection
