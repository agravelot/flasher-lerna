@extends('layouts.app')

@section('content')
    <div class="container is-centered">
        <div class="columns is-multiline is-centered">
            @each('categories.partials._category_item', $categories, 'category', 'layouts.partials._empty')
        </div>
    </div>
@endsection
