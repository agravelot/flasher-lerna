@extends('admin.admin')

@section('admin-content')

    @include('admin.albums._feilds', [
        'route' => route('admin.albums.index') . '/' .  $album->id,
        'album' => $album,
        'categories' => $categories,
        'cosplayers' => $cosplayers,
    ])

@endsection
