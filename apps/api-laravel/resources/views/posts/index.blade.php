@extends('layouts.app')

@section('content')
    <div class="container is-centered">
        <div class="columns is-multiline is-centered">
            @each('posts._post_item', $posts, 'post', 'layouts.partials._empty')
        </div>
    </div>
@endsection
