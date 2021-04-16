@extends('layouts.app')

@section('content')
    <div class="container is-centered">

        <div class="card large">
            <div class="card-content">
                <div class="media">
                    <div class="media-content">
                        <p class="subtitle is-5">{{ $testimonial->name }}</p>
                    </div>
                </div>
                <div class="content">
                    {{ $testimonial->body }}
                </div>
            </div>

        </div>
    </div>
@endsection
