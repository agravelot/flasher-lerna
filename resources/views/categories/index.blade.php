@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="title">Categories</h1>
        <div class="columns is-multiline">
            @each('categories.partials._category_item', $categories, 'category', 'layouts.partials._empty')
        </div>
        {{ $categories->links() }}
    </div>
@endsection
