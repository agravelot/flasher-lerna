@extends('admin.admin')

@section('admin-content')
    @include('cosplayers.partials._show_item', compact('cosplayer'))
@endsection