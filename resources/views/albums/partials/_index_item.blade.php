
@php
    /** @var \Illuminate\Support\Collection $albums */
    $columnsDesktop = $albums->groupBy(function (\App\Models\PublicAlbum $item, int $key) {
            return $key % 3;
        });

    $columnsTablet = $albums->groupBy(function (\App\Models\PublicAlbum $item, int $key) {
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
                       class="has-margin-right-md">
                        <div class="card album">
                            <div v-if="album.media" class="card-image">
                                <figure class="image">
                                    {{ $album->coverResponsive }}
                                </figure>
                            </div>
                            <div class="card-content">
                                <div class="content">
                                    <h3 class="title is-5">{{ $album->title }}</h3>
                                </div>
                            </div>
                        </div>
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
                       class="has-margin-right-md">
                        <div class="card album">
                            <div v-if="album.media" class="card-image">
                                <figure class="image">
                                    {{ $album->coverResponsive }}
                                </figure>
                            </div>
                            <div class="card-content">
                                <div class="content">
                                    <h3 class="title is-5">{{ $album->title }}</h3>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</section>

<section class="is-hidden-tablet">
    @foreach($albums as $album)
        <a href="{{ route('albums.show', compact('album')) }}"
           class="has-margin-right-md">
            <div class="card album">
                <div v-if="album.media" class="card-image">
                    <figure class="image">
                        {{ $album->coverResponsive }}
                    </figure>
                </div>
                <div class="card-content">
                    <div class="content">
                        <h3 class="title is-5">{{ $album->title }}</h3>
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</section>

{{ $albums->links() }}
