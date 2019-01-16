<div class="container is-centered">

    <h1 class="title is-2 has-text-centered">{{ $category->name }}</h1>

    @if ($category->description)
        <div class="column is-8 is-offset-2">
            <div class="card article">
                <div class="card-content">
                    <div class="content article-body">
                        <p class="has-text-justified">{!! $category->description  !!}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container is-centered">
        <div class="columns is-multiline is-centered">
            @each('albums.partials._index_item', $category->publishedAlbums, 'album', 'layouts.partials._empty')
        </div>
    </div>

</div>