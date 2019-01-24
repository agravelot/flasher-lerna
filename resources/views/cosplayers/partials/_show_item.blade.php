<div class="container is-centered">

    <figure class="image is-96x96 is-centered">
        <img class="is-rounded" src="{{ $cosplayer->getFirstMediaUrl('avatar', 'thumb') }}">
    </figure>
    <h1 class="has-text-centered title is-2">{{ $cosplayer->name }}</h1>


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
                    <p class="has-text-justified">{!! $cosplayer->description  !!}</p>
                </div>
                <div class="tags">
                    @each('categories.partials._category_tag', $cosplayer->categories, 'category')
                </div>
            </div>
        </div>
    </div>

    <div class="container is-centered">
        <div class="columns is-multiline is-centered">
            @each('albums.partials._index_item', $cosplayer->publicAlbums, 'album', 'layouts.partials._empty')
        </div>
        {{--{{ $cosplayer->albums->links() }}--}}
    </div>
</div>