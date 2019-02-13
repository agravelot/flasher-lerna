@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="title">{{ __('My albums') }}</h1>
        <h2 class="subtitle">{{ __('Discover my albums') }}</h2>

        @if($albums)
            <div class="masonry">
                @each('albums.partials._index_item', $albums, 'album')
            </div>
        @else
            @include('layouts.partials._empty')
        @endif

        {{ $albums->links() }}
    </div>
@endsection
