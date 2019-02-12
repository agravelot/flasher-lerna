<div class="column is-2-desktop is-4-tablet is-6-mobile">
    @include('cosplayers.partials._cosplayer_avatar', compact('cosplayer'))
    <a href="{{ route('cosplayers.show', compact('cosplayer')) }}">
        <p class="has-text-centered">
            {{ $cosplayer->name }}
        </p>
    </a>
</div>