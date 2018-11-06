<a href="{{route('cosplayers.show', ['cosplayer' => $cosplayer]) }}">
    <figure class="image column is-2-desktop is-3-tablet is-3-mobile">
        @if ($cosplayer->getFirstMediaUrl('avatar'))
            <img class="is-rounded" src="{{ $cosplayer->getFirstMediaUrl('avatar', 'thumb') }}">
        @else
            <span class="icon has-text-info is-large">
                                        <i class="fas fa-user fa-3x"></i>
                                    </span>
        @endif
        <p class="has-text-centered">
            {{ $cosplayer->name }}
        </p>
    </figure>
</a>