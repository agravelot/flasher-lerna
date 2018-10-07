@extends('admin.admin')

@section('admin-content')
    <div class="container is-centered has-margin-top-md">

        <h1 class="title is-2 has-text-centered">{{ $category->name }}</h1>

        <div class="column is-8 is-offset-2">
            <!-- START ARTICLE -->
            <div class="card article">
                <div class="card-content">
                    <div class="media">
                        <div class="media-content has-text-centered">
                            <p class="title article-title">  {{-- {{ $category->title }}--}}</p>
                            <div class="tags has-addons level-item">
                                {{--<span class="tag is-rounded is-info">{{ $category->email}}</span>--}}
                                <span class="tag is-rounded">{{ $category->created_at->toFormattedDateString() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="content article-body">
                        <p class="has-text-justified">{{ $category->description }}</p>
                    </div>
                </div>
            </div>
            <!-- END ARTICLE -->
        </div>

        @each('albums.partials._index_item', $category->albums, 'album', 'layouts.partials._empty')

    </div>
@endsection
