@if ($albums->isEmpty())
    @include('layouts.partials._empty')
@endif


@php
    /** @var \Illuminate\Support\Collection $albums */
    $columnsDesktop = $albums->groupBy(static function ($item, int $key) {
            return $key % 3;
        });

    $columnsTablet = $albums->groupBy(static function ($item, int $key) {
            return $key % 2;
        });

@endphp

<section class="is-hidden-touch">
    <div class="columns">
        @foreach($columnsDesktop as $columnKey => $column)
            <div class="column is-one-third">
                @foreach($column as $mediaKey => $album)
                    @php($key = $columnKey + $mediaKey)
                    <a href="{{ route('albums.show', compact('album')) }}"
                       class="has-margin-right-md" aria-label="{{ $album->title }}">
                        <article class="card has-hover-zoom is-clipped">
                            @if ($album->coverResponsive)
                                <div class="card-image">
                                    <figure class="image">
                                        {{ $album->coverResponsive }}
                                    </figure>
                                </div>
                            @endif
                            <div class="card-content">
                                <div class="content">
                                    <h3 class="title">{{ $album->title }}</h3>
                                    @if ($album->categories->isNotEmpty())
                                        <div class="tags">
                                            @foreach($album->categories as $category)
                                                <span class="tag">
                                                {{ $category->name }}
                                            </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</section>

<section class="is-hidden-desktop is-hidden-mobile">
    <div class="columns is-variable is-1">
        @foreach($columnsTablet as $columnKey => $column)
            <div class="column is-half">
                @foreach($column as $mediaKey => $album)
                    @php($key = $columnKey + $mediaKey)
                    <a href="{{ route('albums.show', compact('album')) }}"
                       class="has-margin-right-md" aria-label="{{ $album->title }}">
                        <article class="card has-hover-zoom is-clipped">
                            @if ($album->coverResponsive)
                                <div class="card-image">
                                    <figure class="image">
                                        {{ $album->coverResponsive }}
                                    </figure>
                                </div>
                            @endif
                            <div class="card-content">
                                <div class="content">
                                    <h3 class="title">{{ $album->title }}</h3>
                                    @if ($album->categories->isNotEmpty())
                                        <div class="tags">
                                            @foreach($album->categories as $category)
                                                <span class="tag">
                                                {{ $category->name }}
                                            </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</section>

<section class="is-hidden-tablet">
    @foreach($albums as $album)
        <div class="has-margin-top-md">
            <a href="{{ route('albums.show', compact('album')) }}" aria-label="{{ $album->title }}">
                <article class="card has-hover-zoom is-clipped">
                    @if ($album->coverResponsive)
                        <div class="card-image">
                            <figure class="image">
                                {{ $album->coverResponsive }}
                            </figure>
                        </div>
                    @endif
                    <div class="card-content">
                        <div class="content">
                            <h3 class="title">{{ $album->title }}</h3>
                            @if ($album->categories->isNotEmpty())
                                <div class="tags">
                                    @foreach($album->categories as $category)
                                        <span class="tag">
                                                {{ $category->name }}
                                            </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            </a>
        </div>
    @endforeach
</section>

{{ $albums->links() }}
