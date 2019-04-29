<div class="container is-centered">

    <h1 class="title is-2 has-text-centered">{{ $category->name }}</h1>

    @if ($category->description)
        <p class="has-text-justified has-margin-bottom-md">{!! $category->description !!}</p>
    @endif

    @php
        /** @var \App\Models\Category $category */
        $albums = \Modules\Album\Transformers\AlbumIndexResource::collection($category->load('publishedAlbums.media')->publishedAlbums);
    @endphp
    @include('albums.partials._index_item', compact('albums'))
</div>