@extends('layouts.app')

@section('content')
    <div class="container is-centered">

        <div class="card large">
            <div class="card-image">
                <figure class="image">
                    <img src="https://images.unsplash.com/photo-1475778057357-d35f37fa89dd?dpr=1&amp;auto=compress,format&amp;fit=crop&amp;w=1920&amp;h=&amp;q=80&amp;cs=tinysrgb&amp;crop="
                         alt="Image" title="" style="">
                </figure>
            </div>
            <div class="card-content">
                <div class="media">
                    <div class="media-content">
                        <p class="subtitle is-5">{{ $album->title }}</p>
                    </div>
                </div>
                <div class="content">
                    {{ $album->body }}
                </div>
            </div>

        </div>
    </div>
@endsection
