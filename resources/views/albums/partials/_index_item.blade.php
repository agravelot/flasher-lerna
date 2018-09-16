<div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
    <a href="{{ route('albums.show', ['album' => $album]) }}">
        <div class="card large">
            <div class="card-image">
                <figure class="image">
                    @foreach($album->pictures as $picture)
                        @if($loop->first)
                            <img src="{{ asset('storage/'.$picture->filename) }}"
                                 alt="Image" title="" style="">
                        @endif
                    @endforeach
                </figure>
            </div>
            <div class="card-content">
                <div class="media">
                    <div class="media-content">
                        <p class="subtitle is-5">{{ $album->title }}</p>
                    </div>
                </div>
                <div class="content">
                    <div class="tags">
                        @foreach($album->categories as $category)
                            <span class="tag">{{$category->name}}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

