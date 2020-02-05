@extends('layouts.app')

@section('pageTitle', __('My albums'))

@section('content')

    @include('layouts.partials._messages')

    <section class="section container">
        <div class="card">
            <div class="card-content">
                @if ($albums->isNotEmpty())
                    <div class="">
                        @foreach($albums as $album)
                            <article class="media">
                                @if ($album->cover)
                                    <div class="media-left">
                                        <figure class="image is-64x64">
                                            <img src="{{ $album->cover->getUrl('thumb') }}" alt="{{ __('Cover of album') }} {{ $album->title }}">
                                        </figure>
                                    </div>
                                @endif
                                <div class="media-content">
                                    <div class="content">
                                        <a href="{{ route('albums.show', compact('album')) }}">
                                            {{ $album->title }}
                                        </a>
                                        <br>
                                        <span>{{ $album->media->count() }} {{ __('photos') }} - {{ Spatie\MediaLibrary\Helpers\File::getHumanReadableSize($album->media->pluck('size')->sum()) }}</span>
                                    </div>
                                </div>
                                <div class="media-right">
                                    <a class="button" href="{{ route('download-albums.show', compact('album')) }}">
                                        <span class="icon is-small">@fas('download')</span>
                                        <span>{{ __('Download') }}</span>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    {{ $albums->links() }}
                @else
                    <span>{{ __('Nothing to show') }}</span>
                @endif
            </div>
        </div>
    </section>
@endsection
