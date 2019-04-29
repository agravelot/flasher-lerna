<div class="container is-centered">

    <h2 class="title is-2 has-text-centered">{{ $user->name }}</h2>

    @if ($user->description)
        <p class="has-text-justified">{{ $user->description }}</p>
    @endif

    @php
        /** @var \App\Models\User $user */
        $albums = \Modules\Album\Transformers\AlbumIndexResource::collection($user->load('albums.media')->albums);
    @endphp
    @include('albums.partials._index_item', compact('albums'))

</div>