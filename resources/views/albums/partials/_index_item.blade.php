<div class="column is-half-tablet is-one-third-widescreen">
    <a href="{{ route('albums.show', ['album' => $album]) }}">
        <div class="card album">
            <div class="card-image">
                <figure class="image">
                     {{ $album->getFirstMedia('pictures') }}
                </figure>
            </div>

            <div class="card-content">
                <div class="content">
                    <p class="title is-5">{{ $album->title }}</p>
                </div>
            </div>
        </div>
    </a>
</div>

