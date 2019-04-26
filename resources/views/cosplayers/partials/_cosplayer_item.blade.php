<div class="column is-half-tablet is-one-third-widescreen">
    <a href="{{ route('cosplayers.show', compact('cosplayer')) }}">
        <div class="card large">
            <div class="card-image">
                <figure class="image">
                    {{ $cosplayer->getFirstMedia('avatar') }}
                </figure>
            </div>
            <div class="card-content">
                <div class="media">
                    <div class="media-content">
                        <h2 class="subtitle is-5">{{ $cosplayer->name }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>
