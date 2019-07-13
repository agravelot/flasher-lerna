<section class="section">
    <div class="container is-centered">

        @include('cosplayers.partials._cosplayer_avatar', compact('cosplayer'))
        {{--    <h1 class="has-text-centered title is-2">{{ $cosplayer->name }}</h1>--}}

        @if ($cosplayer->description or $cosplayer->categories)
            <div class="card has-margin-top-md">
                <div class="card-content">
                    @if ($cosplayer->description)
                        <p class="has-text-justified has-margin-bottom-sm">{!! $cosplayer->description  !!}</p>
                    @endif
                    @if ($cosplayer->categories)
                        <div class="tags">
                            @each('categories.partials._category_tag', $cosplayer->categories, 'category')
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <section class="has-margin-top-md">
            @include('albums.partials._index_item', compact('albums'))
        </section>
    </div>
</section>
