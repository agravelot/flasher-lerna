@extends('layouts.app')

@section('pageTitle', __('My albums'))

@section('content')

    @include('layouts.partials._messages')

    <div class="container has-text-centered">
        @forelse($albums as $album)
            <p>{{ $album->title }}</p>
        @empty
            <span>Nothing to show</span>
        @endforelse
    </div>
@endsection
