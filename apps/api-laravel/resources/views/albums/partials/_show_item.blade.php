<article>
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

            @if(Gate::check('download', $album) || Gate::check('update', $album))
                <div class="columns is-mobile is-centered">
                    <div class="column is-narrow field has-addons">
                        @can('download', $album)
                            <div class="control">
                                <a class="button is-black" href="{{ route('download-albums.show', compact('album')) }}">
                                    <span class="icon is-small">
                                        <x-fa name="download" library="solid"/>
                                    </span>
                                    <span>{{ __('Download') }}</span>
                                </a>
                            </div>
                        @endcan

                        @can('update', $album)
                            <div class="control">
                                <a class="button is-black" href="https://{{ config('app.admin_url')."/albums/{$album->slug}" }}">
                                    <span class="icon is-small">
                                        <x-fa name="edit" library="solid"/>
                                    </span>
                                    <span>{{ __('Edit') }}</span>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>
            @endif

            <div class="has-text-right">
                @include('layouts.partials._share_socials')
            </div>
    @stop

    @if ($album->body)
        <div class="card has-margin-bottom-md" style="margin-top: -170px;">
            <div class="card-content">
                @if ($album->body)
                    <div class="content has-text-justified">
                        {!! $album->body !!}
                    </div>
                @endif
            </div>
        </div>
    @endif

    @php
        /** @var \App\Models\Album $album */
        $medias = $album->getMedia(\App\Models\Album::PICTURES_COLLECTION);

        $columnsDesktop = $medias->groupBy(function (\Spatie\MediaLibrary\MediaCollections\Models\Media $item, int $key) {
                return $key % 3;
            });

        $columnsTablet = $medias->groupBy(function (\Spatie\MediaLibrary\MediaCollections\Models\Media $item, int $key) {
                return $key % 2;
            });
    @endphp

    <section class="is-hidden-touch">
        <div class="columns is-variable is-1">
            @foreach($columnsDesktop as $columnKey => $column)
                <div class="column is-one-third">
                    @foreach($column as $mediaKey => $media)
                        @php($key = $mediaKey * 3 + $columnKey)
                        <div class="modal-button is-inline-block has-hover-zoom is-shadowless has-text-hover-image is-clickable"
                           data-target="{{ "media-{$key}" }}">
                            {{
                               $media(
                                   \App\Models\Album::RESPONSIVE_PICTURES_CONVERSION,
                                   [
                                       'class' => 'modal-button responsive-media-lazy'
                                   ]
                               )
                           }}
                            <span class="icon has-text-white has-text-hover-image-bottom-left">
                                <x-fa name="search"/>
                            </span>
                        </div>
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
                        <div class="modal-button is-inline-block has-hover-zoom is-shadowless has-text-hover-image is-clickable"
                           data-target="{{ "media-{$key}" }}">
                            {{
                               $media(
                                   \App\Models\Album::RESPONSIVE_PICTURES_CONVERSION,
                                   [
                                       'class' => 'modal-button responsive-media-lazy'
                                   ]
                               )
                           }}
                            <span class="icon has-text-white has-text-hover-image-bottom-left">
                                <x-fa name="search"/>
                            </span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>

    <section class="is-hidden-tablet">
        <div class="columns is-variable is-1">
            @foreach($medias as $key => $media)
                <div class="column">
                    <div class="modal-button is-inline-block has-hover-zoom is-shadowless has-text-hover-image is-clickable"
                       data-target="{{ "media-{$key}" }}">
                        {{
                           $media(
                               \App\Models\Album::RESPONSIVE_PICTURES_CONVERSION,
                               [
                                   'class' => 'modal-button responsive-media-lazy'
                               ]
                           )
                       }}
                        <span class="icon has-text-white has-text-hover-image-bottom-left">
                            <x-fa name="search"/>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    @foreach ($medias as $key => $media)
        <div id="{{ "media-{$key}" }}" class="modal modal-fx-slideBottom is-modal-navigable">
            <div class="modal-background"></div>
            <div class="modal-content is-image is-huge is-clipped">
                {{ $media(\App\Models\Album::RESPONSIVE_PICTURES_CONVERSION, ['class' => null]) }}
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
            <h2 class="title is-2 has-text-centered">{{ __('Cosplayers') }}</h2>

            <div class="card">
                <div class="card-content">
                    <ul class="columns is-multiline is-mobile">
                        @foreach($album->cosplayers as $cosplayer)
                            <li class="column">
                                @include('cosplayers.partials._cosplayer_badge', compact('cosplayer'))
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endif
</article>

