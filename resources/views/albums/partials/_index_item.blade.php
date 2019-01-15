<div class="column is-half-tablet is-one-third-widescreen">
    <a href="{{ route('albums.show', ['album' => $album]) }}">
        <div class="card large">
            <div class="card-image">
                <figure class="image">
                    <img src="{{ $album->getFirstMediaUrl('pictures', 'thumb')  }}" alt="">
                </figure>
            </div>

            <div class="card-content">
                <div class="media">
                    <div class="media-content">
                        <p class="subtitle is-5">{{ $album->title }}</p>
                    </div>
                </div>
                <div class="content">

                </div>
                @if (isset($album->categories))
                    <div class="tags">
                        @foreach($album->categories as $category)
                            <div class="tag">
                                <a href="{{ route('categories.show', ['category' => $category]) }}">
                                    {{ $category->name }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </a>
</div>

