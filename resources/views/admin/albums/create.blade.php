@extends('admin.admin')

@section('admin-content')

    @include('admin.albums._feilds', [
        'route' => route('admin.albums.store'),
        'categories' => $categories,
        'cosplayers' => $cosplayers,
    ])

@endsection
