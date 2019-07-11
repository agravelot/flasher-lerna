<div class="container is-centered">

    <h2 class="title is-2 has-text-centered">{{ $user->name }}</h2>

    @if ($user->description)
        <p class="has-text-justified">{{ $user->description }}</p>
    @endif

    @php
        /** @var \App\Models\User $user */
        $albums = $user->load('albums.media')->albums()->paginate();
    @endphp
    @include('albums.partials._index_item', compact('albums'))

</div>
