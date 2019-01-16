<div class="column is-half-tablet is-one-third-widescreen">
    <a href="{{ route('albums.show', ['album' => $album]) }}">
        <div class="card">
            <div class="card-image">
                <figure class="image">
                    <img src="{{ $album->getFirstMediaUrl('pictures', 'thumb')  }}" alt="">
                </figure>
            </div>

            <div class="card-content">
                <div class="content">
                    <p class="title is-5">{{ $album->title }}</p>
                </div>
                @if (!$album->categories->isEmpty())
                    <div class="tags has-margin-top-md">
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

