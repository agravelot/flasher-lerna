<div class="column is-2-desktop is-3-tablet is-3-mobile">
        @if ($cosplayer->getFirstMediaUrl('avatar'))
            <figure class="is-centered image is-96x96">
                <img class="is-rounded" src="{{ $cosplayer->getFirstMediaUrl('avatar', 'thumb') }}">
            </figure>
        @else
            <div class="is-centered avatar-circle">
                <span class="initials"> {{ $cosplayer->initial }}</span>
            </div>
        @endif
    <a href="{{route('cosplayers.show', ['cosplayer' => $cosplayer]) }}">
        <p class="has-text-centered">
            {{ $cosplayer->name }}
        </p>
    </a>
</div>