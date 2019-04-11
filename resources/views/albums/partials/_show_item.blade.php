<div id="app">{{--<div class="container is-centered has-margin-top-md">--}}
    {{--<h1 class="title is-2 has-text-centered">{{ $album->title }}</h1>--}}

    <albums-show-gallery></albums-show-gallery>
</div>

{{--<div class="container is-centered has-margin-top-md">--}}
    {{--<h1 class="title is-2 has-text-centered">{{ $album->title }}</h1>--}}

    {{--<div class="masonry gutterless">--}}
        {{--@foreach ($album->getMedia('pictures') as $key => $picture)--}}
            {{--<a class="modal-button brick has-anchor" data-target="modal-{{ $key }}">--}}
                {{--{{ $picture }}--}}
            {{--</a>--}}
        {{--@endforeach--}}
    {{--</div>--}}

    {{--<div class="column is-10 is-offset-1">--}}
        {{--<div class="card article">--}}
            {{--<div class="card-content has-text-centered">--}}
                {{--<div class="field has-addons">--}}
                    {{--@can('download', $album)--}}
                        {{--<div class="control">--}}
                            {{--<a class="button" href="{{ route('download-albums.show', compact('album')) }}">--}}
                                {{--<span class="icon is-small">--}}
                                    {{--<i class="fas fa-download"></i>--}}
                                {{--</span>--}}
                                {{--<span>{{ __('Download') }}</span>--}}
                            {{--</a>--}}
                        {{--</div>--}}
                    {{--@endcan--}}
                    {{--@can('update', $album)--}}
                        {{--<div class="control">--}}
                            {{--<a class="button" href="{{ route('admin.albums.edit', compact('album')) }}">--}}
                                {{--<span class="icon is-small">--}}
                                    {{--<i class="fas fa-edit"></i>--}}
                                {{--</span>--}}
                                {{--<span>{{ __('Edit') }}</span>--}}
                            {{--</a>--}}
                        {{--</div>--}}
                    {{--@endcan--}}
                {{--</div>--}}

                {{--<div class="tags has-addons level-item">--}}
                    {{--<span class="tag is-rounded is-info">{{'@' . $album->user->name}}</span>--}}
                    {{--<span class="tag is-rounded">{{ $album->created_at->toFormattedDateString() }}</span>--}}
                {{--</div>--}}
                {{--<div class="content article-body">--}}
                    {{--<p class="has-text-justified">{!! $album->body !!}</p>--}}
                {{--</div>--}}
                {{--<div class="tags">--}}
                    {{--@each('categories.partials._category_tag', $album->categories, 'category')--}}
                {{--</div>--}}
                {{--<div class="columns is-multiline is-mobile">--}}
                    {{--@each('cosplayers.partials._cosplayer_badge', $album->cosplayers, 'cosplayer')--}}
                {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}

{{--@foreach ($album->getMedia('pictures') as $key => $picture)--}}
    {{--<div id="modal-{{ $key }}" class="modal modal-fx-fadeInScale">--}}
        {{--<div class="modal-background"></div>--}}
        {{--<div class="modal-content is-huge is-image">--}}
            {{--<img srcset="{{ $picture->getSrcset() }}" src="{{ $picture->getUrl() }}" alt="{{ $picture->name }}">--}}
        {{--</div>--}}
        {{--<button class="modal-close is-large" aria-label="{{ __('close') }}"></button>--}}
    {{--</div>--}}
{{--@endforeach--}}
