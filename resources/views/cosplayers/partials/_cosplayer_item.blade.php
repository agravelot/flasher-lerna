<div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-third-fullhd">
    <a href="{{ route('cosplayers.show', ['cosplayer' => $cosplayer]) }}">
        <div class="card large">
            <div class="card-image">
                <figure class="image">
                    {{--@foreach($cosplayer->avatar as $picture)--}}
                    {{--<img src="{{ asset('storage/'.$picture->filename) }}"--}}
                    {{--alt="Image" title="" style="">--}}
                    {{--@endforeach--}}
                </figure>
            </div>
            <div class="card-content">
                <div class="media">
                    <div class="media-content">
                        <p class="subtitle is-5">{{ $cosplayer->title }}</p>
                    </div>
                </div>
                <div class="content">
                    <div class="tags">
                        <span class="tag">One</span>
                        <span class="tag">Two</span>
                        <span class="tag">Three</span>
                    </div>

                    {{--{{ $album->body }}--}}

                </div>
            </div>
        </div>
    </a>
</div>
