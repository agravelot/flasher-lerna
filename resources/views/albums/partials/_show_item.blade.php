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

        <div class="media has-margin-top-md has-margin-bottom-md">
            @php
                $profilePicture = settings()->get('profile_picture_homepage');
            @endphp
            @if ($profilePicture)
                <div class="media-left">
                    <figure class="image is-128x128 is-pulled-right">
                        {{ $profilePicture('', ['class' => 'is-rounded responsive-media']) }}
                    </figure>
                </div>
            @endif
            <div class="media-content">
                <div class="content has-text-centered">
                    <a class="has-text-white" href="{{ url('/') }}" rel="author">{{ __('By') }} {{ $album->user->name }}</a>
                </div>
            </div>
        </div>

        @include('layouts.partials._share_socials')
    @stop

    @if ($album->body)
        <div class="card has-margin-bottom-md" style="margin-top: -120px;">
            <div class="card-content">
                @if ($album->body)
                    <div class="content has-text-justified is-family-secondary has-text-black">
                        {!! $album->body !!}
                    </div>
                @endif
            </div>
        </div>
    @endif

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
                                   \App\Models\Album::RESPONSIVE_PICTURES_CONVERSION,
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
                                   \App\Models\Album::RESPONSIVE_PICTURES_CONVERSION,
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
                    <a class="modal-button is-inline-block has-hover-zoom is-shadowless has-text-hover-image"
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


