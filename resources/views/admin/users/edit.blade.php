@extends('admin.admin')

@section('admin-content')
    <div class="card">
        <div class="card-content">
            @include('admin.users._feilds', ['route' => route('admin.users.update', compact('user'))])
        </div>
    </div>
@endsection