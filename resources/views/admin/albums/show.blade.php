@extends('admin.admin')

@section('admin-content')
    @include('albums.partials._show_item', ['album' => $album])
@endsection
