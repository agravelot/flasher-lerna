<a class="brick" href="{{ route('albums.show', compact('album'))  }}">
    <div class="card album">
        <div class="card-image">
            <figure>
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

