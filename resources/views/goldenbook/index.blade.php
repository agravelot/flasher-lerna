@extends('layouts.app')

@section('content')
    <div class="container is-centered">
        <div class="columns is-multiline is-centered">
            @each('goldenbook._goldenbook_item', $goldenBooksPosts, 'goldenBookPost', 'layouts.partials._empty')
        </div>
    </div>
@endsection
