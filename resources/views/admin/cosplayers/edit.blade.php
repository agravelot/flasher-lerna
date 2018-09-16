@extends('admin.admin')

@section('admin-content')
    <div class="container">
        <div class="card-content">
            @include('admin.cosplayers._feilds', ['route' => route('admin.cosplayers.index') . '/' .  $cosplayer->id])
        </div>
    </div>
@endsection