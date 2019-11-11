@extends('layouts.app')

@section('pageTitle', __('My albums'))

@section('content')

    @include('layouts.partials._messages')

    <section class="section container">
        <div class="content">
            <ul>
                @forelse($albums as $album)
                    <li>
                        {{ $album->title }}
                        <a class="button" href="{{ route('albums.show', compact('album')) }}">
                            <span class="icon is-small">@fas('eye')</span>
                            <span>{{ __('Open') }}</span>
                        </a>
                        <a class="button" href="{{ route('download-albums.show', compact('album')) }}">
                            <span class="icon is-small">@fas('download')</span>
                            <span>{{ __('Download') }}</span>
                        </a>
                    </li>
                @empty
                    <span>{{ __('Nothing to show') }}</span>
                @endforelse
            </ul>
        </div>
        {{ $albums->links() }}
    </section>
@endsection
