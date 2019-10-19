@extends('admin.admin')

@section('admin-content')
    <div class="card">
        @include('admin.socialmedias._feilds', [ 'route' => route('admin.social-medias.store') ])
    </div>
@endsection
