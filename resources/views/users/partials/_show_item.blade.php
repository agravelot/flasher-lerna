<div class="container is-centered">

    <h2 class="title is-2 has-text-centered">{{ $user->name }}</h2>

    <div class="column is-8 is-offset-2">
        <div class="card article">
            <div class="card-content">
                <div class="media">
                    <div class="media-content has-text-centered">
                        <p class="title article-title">  {{-- {{ $album->title }}--}}</p>
                        <div class="tags has-addons level-item">
                            {{--<span class="tag is-rounded is-info">{{'@' . $album->user->name}}</span>--}}
                            {{--<span class="tag is-rounded">{{ $album->created_at->toFormattedDateString() }}</span>--}}
                        </div>
                    </div>
                </div>
                <div class="content article-body">
                    <p class="has-text-justified">{{ $user->description }}</p>
                </div>
                <div class="tags">
                    {{--@foreach($user->categories as $category)--}}
                    {{--<span class="tag">{{ $category->name }}</span>--}}
                    {{--@endforeach--}}
                </div>
                <div class="columns is-multiline is-mobile">
                    {{--@for ($i = 1; $i <= 10; $i++)--}}
                    {{--<figure class="image column is-2-desktop is-3-tablet is-3-mobile">--}}
                    {{--<img class="is-rounded" src="https://bulma.io/images/placeholders/128x128.png">--}}
                    {{--<p class="has-text-centered"><a href="#">Cosplayer {{ $i }}</a></p>--}}
                    {{--</figure>--}}
                    {{--@endfor--}}

                </div>
            </div>
        </div>


    </div>

    <div class="container is-centered">
        <div class="columns is-multiline is-centered">
            @each('albums.partials._index_item', $user->albums, 'album', 'layouts.partials._empty')
        </div>
        {{--{{ $user->albums->links() }}--}}
    </div>

</div>