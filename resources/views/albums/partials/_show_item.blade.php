<div class="field has-addons">
    @can('download', $album)
        <div class="control">
            <a class="button" href="{{ route('download-albums.show', compact('album')) }}">
                <span class="icon is-small"><i class="fas fa-download"></i></span>
                <span>Download</span>
            </a>
        </div>
    @endcan

    @can('update', $album)
        <div class="control">
            <a class="button" href="{{ "/admin/albums/{$album->slug}/edit" }}">
                <span class="icon is-small"><i class="fas fa-edit"></i></span>
                <span>Edit</span>
            </a>
        </div>
    @endcan
</div>

@if ($album->body or $album->categories->count())
    <div class="card has-margin-bottom-md">
        <div class="card-content">
            @if ($album->body)
                <div class="content article-body">
                    <p class="has-text-justified">
                        {{ $album->body }}
                    </p>
                </div>
            @endif

            @if ($album->categories and $album->categories->count())
                <div class="tags">
                    @foreach($album->categories as $category)
                        <span class="tag">
                        <a href=" {{ route('categories.show', ['category'=> $category]) }}">
                            {{ $category->name }}
                        </a>
                    </span>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endif

@php
    /** @var \App\Models\Album $album */
    $medias = $album->getMedia(\App\Models\Album::PICTURES_COLLECTION);

    $columnsDesktop = $medias->groupBy(function (\Spatie\MediaLibrary\Models\Media $item, int $key) {
            return $key % 3;
        });

    $columnsTablet = $medias->groupBy(function (\Spatie\MediaLibrary\Models\Media $item, int $key) {
            return $key % 2;
        });

@endphp

<section class="is-hidden-touch">
    <div class="columns is-variable is-1">
        @foreach($columnsDesktop as $columnKey => $column)
            <div class="column is-one-third">
                @foreach($column as $mediaKey => $media)
                    @php($key = $columnKey + $mediaKey)
                    <a class="modal-button is-inline-block" data-target="{{ "media-{$key}" }}">
                        {{
                           $media(
                               \Modules\Album\Entities\Album::RESPONSIVE_PICTURES_CONVERSION,
                               [
                                   'class'=>'modal-button responsive-media'
                               ]
                           )
                       }}
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
                @foreach($column as $mediaKey => $media)
                    @php($key = $columnKey + $mediaKey)
                    <a class="modal-button is-inline-block" data-target="{{ "media-{$key}" }}">
                        {{
                           $media(
                               \Modules\Album\Entities\Album::RESPONSIVE_PICTURES_CONVERSION,
                               [
                                   'class'=>'modal-button responsive-media'
                               ]
                           )
                       }}
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>
</section>

<section class="is-hidden-tablet">
    <div class="columns is-variable is-1">
        @foreach($medias as $key => $media)
            <div class="column">
                <a class="modal-button is-inline-block" data-target="{{ "media-{$key}" }}">
                    {{
                       $media(
                           \Modules\Album\Entities\Album::RESPONSIVE_PICTURES_CONVERSION,
                           [
                               'class'=>'modal-button responsive-media'
                           ]
                       )
                   }}
                </a>
            </div>
        @endforeach
    </div>
</section>

@foreach ($medias as $key => $media)
    <div id="{{ "media-{$key}" }}" class="modal modal-fx-fadeInScale is-modal-navigable">
        <div class="modal-background"></div>
        <div class="modal-content is-image is-huge">
            {{ $media }}
            {{ $key + 1 . '/' . $medias->count() }}
        </div>
        <button class="modal-close is-large" aria-label="close"></button>
    </div>
@endforeach

@if ($album->cosplayers->count())
    <section class="section">
        <h2 class="title is-2 has-text-centered">Cosplayers</h2>

        <div class="card">
            <div class="card-content">
                <div class="columns is-multiline is-mobile">
                    @foreach($album->cosplayers as $cosplayer)
                        <div class="column">
                            @if ($cosplayer->avatar)
                                <figure class="is-centered image is-64x64">
                                    <img class="is-rounded" src="{{ $cosplayer->avatar->getUrl() }}"/>
                                </figure>
                            @else
                                <figure class="is-centered avatar-circle"
                                        style="background-color: {{ string_to_color($cosplayer->name) }}">
                                    <span class="initials">
                                        {{ $cosplayer->initial }}
                                    </span>
                                </figure>
                            @endif
                            <a href="{{ route('cosplayers.show', ['cosplayer' => $cosplayer]) }}">
                                <p class="has-text-centered has-margin-top-sm">
                                    {{ $cosplayer->name }}
                                </p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif


