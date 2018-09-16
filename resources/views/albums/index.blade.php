@extends('layouts.app')

@section('content')

    @each('albums.partials._index_item', $albums, 'album', 'admin.partials._empty')

@endsection
