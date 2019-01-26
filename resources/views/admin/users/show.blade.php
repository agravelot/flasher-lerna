@extends('admin.admin')

@section('admin-content')
    @include('users.partials._show_item', compact('user'))
@endsection