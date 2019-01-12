@extends('admin.admin')

@section('admin-content')

    @include('admin.albums._feilds', [
        'route' => route('admin.albums.update', ['album' => $album]),
        'album' => $album,
        'categories' => $categories,
        'cosplayers' => $cosplayers,
        'currentDate' => $currentDate,
    ])

@endsection
