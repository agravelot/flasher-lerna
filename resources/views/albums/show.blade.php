@extends("layouts.app")

@section("content")
    <div class="container is-centered">

        <h1 class="title is-2 has-text-centered">{{ $album->title }}</h1>

        <div class="carousel carousel-animated carousel-animate-slide">
            <div class="carousel-container">
                <div class="carousel-item has-background is-active">
                    <img class="is-background"
                         src="https://images.unsplash.com/photo-1475778057357-d35f37fa89dd?dpr=1&amp;auto=compress,format&amp;fit=crop&amp;w=1920&amp;h=&amp;q=80&amp;cs=tinysrgb&amp;crop="
                         alt="" width="640" height="310"/>
                    {{--<div class="title">Merry Christmas</div>--}}
                </div>
                <div class="carousel-item has-background">
                    <img class="is-background" src="https://wikiki.github.io/images/singer.jpg" alt="" width="640"
                         height="310"/>
                    <div class="title">Original Gift: Offer a song with <a href="https://lasongbox.com" target="_blank">La
                            Song Box</a></div>
                </div>
                <div class="carousel-item has-background">
                    <img class="is-background" src="https://wikiki.github.io/images/sushi.jpg" alt="" width="640"
                         height="310"/>
                    <div class="title">Sushi time</div>
                </div>
                <div class="carousel-item has-background">
                    <img class="is-background" src="https://wikiki.github.io/images/life.jpg" alt="" width="640"
                         height="310"/>
                    <div class="title">Life</div>
                </div>
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
            <!-- START ARTICLE -->
            <div class="card article">
                <div class="card-content">
                    <div class="media">
                        <div class="media-content has-text-centered">
                            <p class="title article-title">{{ $album->title }}</p>
                            <div class="tags has-addons level-item">
                                <span class="tag is-rounded is-info">@ {{$album->user->name}}</span>
                                <span class="tag is-rounded">{{ $album->created_at->toFormattedDateString() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="content article-body">
                        <p class="has-text-justified">{{ $album->body }}</p>
                    </div>
                    <div class="tags">
                        <span class="tag">One</span>
                        <span class="tag">Two</span>
                        <span class="tag">Three</span>
                        <span class="tag">Four</span>
                        <span class="tag">Five</span>
                        <span class="tag">Six</span>
                        <span class="tag">Seven</span>
                        <span class="tag">Eight</span>
                        <span class="tag">Nine</span>
                        <span class="tag">Ten</span>
                        <span class="tag">Eleven</span>
                        <span class="tag">Twelve</span>
                        <span class="tag">Thirteen</span>
                        <span class="tag">Fourteen</span>
                        <span class="tag">Fifteen</span>
                        <span class="tag">Sixteen</span>
                        <span class="tag">Seventeen</span>
                        <span class="tag">Eighteen</span>
                        <span class="tag">Nineteen</span>
                        <span class="tag">Twenty</span>
                    </div>
                    <div class="columns is-multiline is-mobile">
                        @for ($i = 1; $i <= 10; $i++)
                            <figure class="image column is-2-desktop is-3-tablet is-3-mobile">
                                <img class="is-rounded" src="https://bulma.io/images/placeholders/128x128.png">
                                <p class="has-text-centered"><a href="#">Cosplayer {{ $i }}</a></p>
                            </figure>
                        @endfor


                    </div>

                </div>
            </div>
            <!-- END ARTICLE -->
        </div>

    </div>
@endsection
