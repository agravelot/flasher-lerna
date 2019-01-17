<div class="container is-centered has-margin-top-md">

    <h1 class="title is-2 has-text-centered">{{ $album->title }}</h1>


    @foreach ($album->getMedia('pictures') as $picture)
        {{ $picture }}
    @endforeach


    <div class="column is-8 is-offset-2">
        <div class="card article">
            <div class="card-content">
                <div class="media">
                    <div class="media-content has-text-centered">
                        <p class="title article-title">  {{-- {{ $album->title }}--}}</p>

                        <div class="field has-addons">
                            @can('download', $album)
                                <p class="control">
                                    <a class="button" href="{{ route('download-albums.show', compact('album')) }}">
                                        <span class="icon is-small">
                                           <i class="fas fa-download"></i>
                                        </span>
                                        <span>Download</span>
                                    </a>
                                </p>
                            @endcan
                            @can('update', $album)
                                <p class="control">
                                    <a class="button" href="{{ route('admin.albums.edit', ['album' => $album]) }}">
                                        <span class="icon is-small">
                                            <i class="fas fa-edit"></i>
                                        </span>
                                        <span>Edit</span>
                                    </a>
                                </p>
                            @endcan
                        </div>

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
                    @each('categories.partials._category_tag', $album->categories, 'category')
                </div>
                <div class="columns is-multiline is-mobile">
                    @each('cosplayers.partials._cosplayer_badge', $album->cosplayers, 'cosplayer')
                </div>

            </div>
        </div>
    </div>
</div>
