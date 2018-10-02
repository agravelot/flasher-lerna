<div class="container is-centered has-margin-top-md">

    <h1 class="title is-2 has-text-centered">{{ $album->title }}</h1>

    <div class="carousel carousel-animated carousel-animate-slide">
        <div class="carousel-container">
            @foreach ($album->pictures as $picture)
                <div class="carousel-item has-background @if ($loop->first) is-active @endif">
                    <img class="is-background" src="{{ asset('storage/'.$picture->filename)}}" alt="" width="640"
                         height="310"/>
                    {{--<div class="title">Original Gift: Offer a song with <a href="https://lasongbox.com" target="_blank">La--}}
                    {{--Song Box</a></div>--}}
                </div>
            @endforeach
        </div>
        <div class="carousel-navigation is-overlay">
            <div class="carousel-nav-left">
                <i class="fa fa-chevron-left" aria-hidden="true"></i>
            </div>
            <div class="carousel-nav-right">
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </div>
        </div>
    </div>

    <div class="column is-8 is-offset-2">
        <div class="card article">
            <div class="card-content">
                <div class="media">
                    <div class="media-content has-text-centered">
                        <p class="title article-title">  {{-- {{ $album->title }}--}}</p>
                        <div class="tags has-addons level-item">
                            <span class="tag is-rounded is-info">{{'@' . $album->user->name}}</span>
                            <span class="tag is-rounded">{{ $album->created_at->toFormattedDateString() }}</span>
                        </div>
                    </div>
                </div>
                <div class="content article-body">
                    <p class="has-text-justified">{!! $album->body  !!}</p>
                </div>
                <div class="tags">
                    @foreach($album->categories as $category)
                        <span class="tag">{{$category->name}}</span>
                    @endforeach
                </div>
                <div class="columns is-multiline is-mobile">
                    @foreach ($album->cosplayers as $cosplayer)
                        <a href="{{route('cosplayers.show', ['cosplayer' => $cosplayer]) }}">
                            <figure class="image column is-2-desktop is-3-tablet is-3-mobile">
                                <img class="is-rounded" src="https://bulma.io/images/placeholders/128x128.png">
                                <p class="has-text-centered">
                                    <a href="{{route('cosplayers.show', ['cosplayer' => $cosplayer]) }}">
                                        {{ $cosplayer->name }}
                                    </a>
                                </p>
                            </figure>
                        </a>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>

@section('js')
    <script src="{{ mix('js/carousel.js') }}"></script>
@endsection
