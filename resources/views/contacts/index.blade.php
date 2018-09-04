@extends('layouts.app')

@section('content')
    <div class="container is-centered">
        @foreach($contacts as $contact)

            <a href="{{ route('albums.show', ['album' => $contact]) }}">
                <div class="card large">
                    <div class="card-image">
                        <figure class="image">
                        </figure>
                    </div>
                    <div class="card-content">
                        <div class="media">
                            <div class="media-content">
                                <p class="subtitle is-5">{{ $contact->title }}</p>
                            </div>
                        </div>
                        <div class="content">
                            <div class="tags">
                                <span class="tag">One</span>
                                <span class="tag">Two</span>
                                <span class="tag">Three</span>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
