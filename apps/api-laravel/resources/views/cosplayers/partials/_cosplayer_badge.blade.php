@include('cosplayers.partials._cosplayer_avatar', compact('cosplayer'))
<a href="{{ route('cosplayers.show', ['cosplayer' => $cosplayer]) }}" aria-label="{{ $cosplayer->name }}">
    <p class="has-text-centered has-margin-top-sm">
        {{ $cosplayer->name }}
    </p>
</a>
