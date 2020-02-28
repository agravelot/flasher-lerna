<div class="column is-half-tablet is-one-third-widescreen">
    <a href="{{ route('cosplayers.show', compact('cosplayer')) }}">
        <div class="box">
            <article class="media">
                <div class="media-left">
                    <figure class="image is-64x64">
                        {{ $cosplayer->getFirstMedia('avatar') }}
                    </figure>
                </div>
                <div class="media-content">
                    <div class="content">
                        <h2 class="subtitle is-5">{{ $cosplayer->name }}</h2>
                    </div>
                </div>
            </article>
        </div>
    </a>
</div>
