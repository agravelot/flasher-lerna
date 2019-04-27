<div class="container is-centered">

    <h1 class="title is-2 has-text-centered">{{ $category->name }}</h1>

    @if ($category->description)
        <p class="has-text-justified">{!! $category->description  !!}</p>
    @endif

    @include('albums.partials._index_item', compact('albums'))
</div>