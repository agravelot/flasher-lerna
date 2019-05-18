<section class="section">
    <div class="container is-centered">

        @if ($category->description)
            <p class="has-text-justified has-margin-bottom-md">{!! $category->description !!}</p>
        @endif

        @php
            /** @var \App\Models\Category $category */
            $albums = \Modules\Album\Transformers\AlbumIndexResource::collection($category->load('publishedAlbums.media')->publishedAlbums);
        @endphp
        @include('albums.partials._index_item', compact('albums'))
    </div>
</section>
