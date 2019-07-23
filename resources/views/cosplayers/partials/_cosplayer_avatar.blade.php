@if ($cosplayer->avatar)
    <figure class="is-centered image is-64x64">
        {{ $cosplayer->getFirstMedia('avatar')('thumb', ['class' => 'is-rounded']) }}
    </figure>
@else
    <figure class="is-centered avatar-circle"
            style="background-color: {{ string_to_color($cosplayer->name) }}">
        <span class="initials">
            {{ $cosplayer->initial }}
        </span>
    </figure>
@endif
