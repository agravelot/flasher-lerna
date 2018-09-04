@extends("layouts.app")

@section("content")
    <div class="container is-centered">

        <h1 class="title is-2 has-text-centered">{{ $contact->title }}</h1>

        <div class="carousel carousel-animated carousel-animate-slide">
            <div class="carousel-container">
                @foreach ($contact->pictures as $pictures)
                    <div class="carousel-item has-background @if ($loop->first) is-active @endif">
                        <img class="is-background" src="{{ asset('storage/'.$pictures->filename)}}" alt="" width="640"
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
            <!-- START ARTICLE -->
            <div class="card article">
                <div class="card-content">
                    <div class="media">
                        <div class="media-content has-text-centered">
                            <p class="title article-title">  {{-- {{ $contact->title }}--}}</p>
                            <div class="tags has-addons level-item">
                                <span class="tag is-rounded is-info">{{'@' . $contact->user->name}}</span>
                                <span class="tag is-rounded">{{ $contact->created_at->toFormattedDateString() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="content article-body">
                        <p class="has-text-justified">{{ $contact->body }}</p>
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
