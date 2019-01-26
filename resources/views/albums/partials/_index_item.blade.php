<div class="column is-half-tablet is-one-third-widescreen">
    <a href="{{ route('albums.show', compact('album'))  }}">
        <div class="card album">
            <div class="card-image">
                <figure class="image">
                     {{ $album->getFirstMedia('pictures') }}
                </figure>
            </div>

            <div class="card-content">
                <div class="content">
                    <h5 class="title is-5">{{ $album->title }}</h5>
                </div>
            </div>
        </div>
    </a>
</div>

