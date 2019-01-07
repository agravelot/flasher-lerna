<div class="column is-half-tablet is-one-third-widescreen">
    <a href="{{ route('cosplayers.show', ['cosplayer' => $cosplayer]) }}">
        <div class="card large">
            <div class="card-image">
                <figure class="image">
                    {{--@foreach($cosplayer->avatar as $picture)--}}
                    {{--<img src="{{ asset('storage/'.$picture->filePath) }}"--}}
                    {{--alt="Image" title="" style="">--}}
                    {{--@endforeach--}}
                </figure>
            </div>
            <div class="card-content">
                <div class="media">
                    <div class="media-content">
                        <p class="subtitle is-5">{{ $cosplayer->name }}</p>
                    </div>
                </div>
                <div>
                    {{ $cosplayer->description }}
                </div>
            </div>
        </div>
    </a>
</div>
