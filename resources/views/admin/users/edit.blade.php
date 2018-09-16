@extends('admin.admin')

@section('admin-content')
    <div class="container">
        <div class="card-content">
            @include('admin.users._feilds', ['route' => route('admin.users.index') . '/' .  $user->id])
        </div>
    </div>
@endsection