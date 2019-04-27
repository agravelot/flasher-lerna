<div class="container is-centered">

    @include('cosplayers.partials._cosplayer_avatar', compact('cosplayer'))
    <h1 class="has-text-centered title is-2">{{ $cosplayer->name }}</h1>

    @if ($cosplayer->description)
        <p class="has-text-justified">{!! $cosplayer->description  !!}</p>
    @endif
    @if ($cosplayer->categories)
        <div class="tags">
            @each('categories.partials._category_tag', $cosplayer->categories, 'category')
        </div>
    @endif

    @include('albums.partials._index_item', compact('albums'))
</div>