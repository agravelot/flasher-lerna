<div class="column is-half-tablet is-one-third-widescreen">
    <a href="{{ route('cosplayers.show', compact('cosplayer')) }}" aria-label="{{ $cosplayer->name }}">
        <div class="box">
            <article class="media">
                <div class="media-left">
                    @include('cosplayers.partials._cosplayer_avatar', compact('cosplayer'))
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
