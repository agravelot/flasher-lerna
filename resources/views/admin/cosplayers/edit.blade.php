@extends('admin.admin')

@section('admin-content')
    <div class="card">
        <div class="card-content">
            @include('admin.cosplayers._feilds', ['route' => route('admin.cosplayers.index') . '/' .  $cosplayer->id])
        </div>
    </div>
@endsection