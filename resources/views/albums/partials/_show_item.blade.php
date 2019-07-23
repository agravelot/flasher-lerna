@section('headerTags')
    @if ($album->categories->isNotEmpty())
        <div class="tags is-centered">
            @foreach($album->categories as $category)
                <span class="tag is-dark">
                    <a href=" {{ route('categories.show', ['category'=> $category]) }}" class="has-text-white">
                        {{ $category->name }}
                    </a>
                </span>
            @endforeach
        </div>
    @endif
@stop

<div class="field has-addons">
    @can('download', $album)
        <div class="control">
            <a class="button" href="{{ route('download-albums.show', compact('album')) }}">
                <span class="icon is-small">@fas('download')</span>
                <span>{{ __('Download') }}</span>
            </a>
        </div>
    @endcan

    @can('update', $album)
        <div class="control">
            <a class="button" href="{{ "/admin/albums/{$album->slug}/edit" }}">
                <span class="icon is-small">@fas('edit')</span>
                <span>{{ __('Edit') }}</span>
            </a>
        </div>
    @endcan
</div>

@if ($album->body)
    <article class="card has-margin-bottom-md">
        <div class="card-content">
            @if ($album->body)
                <div class="content has-text-justified is-family-secondary has-text-black">
                    {!! $album->body !!}
                </div>
            @endif
        </div>
    </article>
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
                    @php($key = $mediaKey * 3 + $columnKey)
                    <a class="modal-button is-inline-block has-hover-zoom is-shadowless has-text-hover-image"
                       data-target="{{ "media-{$key}" }}">
                        {{
                           $media(
                               \Modules\Album\Entities\Album::RESPONSIVE_PICTURES_CONVERSION,
                               [
                                   'class' => 'modal-button responsive-media-lazy'
                               ]
                           )
                       }}
                        <span class="icon has-text-white has-text-hover-image-bottom-left">
                            @fa('search')
                        </span>
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
                    @php($key = $mediaKey * 2 + $columnKey)
                    <a class="modal-button is-inline-block has-hover-zoom is-shadowless has-text-hover-image"
                       data-target="{{ "media-{$key}" }}">
                        {{
                           $media(
                               \Modules\Album\Entities\Album::RESPONSIVE_PICTURES_CONVERSION,
                               [
                                   'class' => 'modal-button responsive-media-lazy'
                               ]
                           )
                       }}
                        <span class="icon has-text-white has-text-hover-image-bottom-left">
                            @fa('search')
                        </span>
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
                <a class="modal-button is-inline-block has-hover-zoom is-shadowless has-text-hover-image" data-target="{{ "media-{$key}" }}">
                    {{
                       $media(
                           \Modules\Album\Entities\Album::RESPONSIVE_PICTURES_CONVERSION,
                           [
                               'class' => 'modal-button responsive-media-lazy'
                           ]
                       )
                   }}
                    <span class="icon has-text-white has-text-hover-image-bottom-left">
                        @fa('search')
                    </span>
                </a>
            </div>
        @endforeach
    </div>
</section>

@foreach ($medias as $key => $media)
    <div id="{{ "media-{$key}" }}" class="modal modal-fx-slideBottom is-modal-navigable">
        <div class="modal-background"></div>
        <div class="modal-content is-image is-huge is-clipped">
            {{ $media(\Modules\Album\Entities\Album::RESPONSIVE_PICTURES_CONVERSION, ['class' => null]) }}
            <span>{{ $key + 1 . '/' . $medias->count() }}</span>
        </div>
        <a class="prev" onclick="this.dispatchEvent(new KeyboardEvent('keydown', {'key':'ArrowLeft'}));"
           aria-label="{{ __('Previous') }}">&#10094;</a>
        <a class="next" onclick="this.dispatchEvent(new KeyboardEvent('keydown', {'key':'ArrowRight'}));"
           aria-label="{{ __('Next') }}">&#10095;</a>
        <button class="modal-close is-large" aria-label="{{ __('Close') }}"></button>
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
                                    {{ $cosplayer->getFirstMedia('avatar')('thumb', ['class' => 'is-rounded']) }}
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


