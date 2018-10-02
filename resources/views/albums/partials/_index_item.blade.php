<div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
    <a href="{{ route('albums.show', ['album' => $album]) }}">
        <div class="card large">
            @if(isset($album->pictureHeader))
                <div class="card-image">
                    <figure class="image">
                        <img src="{{ asset('storage/'.$album->pictureHeader->filename) }}"
                             alt="Image" title="" style="">
                    </figure>
                </div>
            @endif
            <div class="card-content">
                <div class="media">
                    <div class="media-content">
                        <p class="subtitle is-5">{{ $album->title }}</p>
                    </div>
                </div>
                @if (isset($album->categories))
                    <div class="content">
                        <div class="tags">
                            @foreach($album->categories as $category)
                                <span class="tag">{{$category->name}}</span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </a>
</div>

