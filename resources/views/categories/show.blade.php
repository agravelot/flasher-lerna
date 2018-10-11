@extends("layouts.app")

@section("content")
    @include('categories.partials._show_item', ['category' => $category])
@endsection
