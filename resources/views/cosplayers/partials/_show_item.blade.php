<div class="container is-centered">

    @include('cosplayers.partials._cosplayer_avatar', compact('cosplayer'))
    <h1 class="has-text-centered title is-2">{{ $cosplayer->name }}</h1>

    @if ($cosplayer->description && $cosplayer->categories)
        <div class="column is-8 is-offset-2">
            <div class="card article">
                <div class="card-content">
                    <div class="content article-body">
                        <p class="has-text-justified">{!! $cosplayer->description  !!}</p>
                    </div>
                    <div class="tags">
                        @each('categories.partials._category_tag', $cosplayer->categories, 'category')
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('albums.partials._index_item', compact('albums'))
</div>