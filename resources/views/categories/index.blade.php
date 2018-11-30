@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="columns is-multiline">
            @each('categories.partials._category_item', $categories, 'category', 'layouts.partials._empty')
        </div>
    </div>
@endsection
