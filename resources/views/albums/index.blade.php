@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="title">{{ __('My albums') }}</h1>
        <h2 class="subtitle">{{ __('Discover my albums') }}</h2>

        @forelse($albums as $album)
            <div class="masonry">
                @include('albums.partials._index_item', compact('album'))
            </div>
        @empty
            @include('layouts.partials._empty')
        @endforelse

        {{ $albums->links() }}
    </div>
@endsection
