<div class="container is-centered">

    <h2 class="title is-2 has-text-centered">{{ $user->name }}</h2>

    @if ($user->description)
        <div class="column is-8 is-offset-2">
            <div class="card article">
                <div class="card-content">
                    <div class="content article-body">
                        <p class="has-text-justified">{{ $user->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container is-centered">
        <div class="masonry">
            @each('albums.partials._index_item', $user->albums, 'album', 'layouts.partials._empty')
        </div>
        {{--{{ $user->albums->links() }}--}}
    </div>

</div>