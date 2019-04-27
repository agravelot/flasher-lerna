@extends("layouts.app")

@section('pageTitle', $category->name)

@section("content")
    @include('categories.partials._show_item', compact('category', 'albums'))
@endsection
