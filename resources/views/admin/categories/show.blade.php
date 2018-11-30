@extends('admin.admin')

@section('admin-content')
    @include('categories.partials._show_item', ['category' => $category])
@endsection
