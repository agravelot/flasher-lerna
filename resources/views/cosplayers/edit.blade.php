@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card-content">
            @include('cosplayers._feilds', ['route' => route('albums.index') . '/' .  $cosplayer->id])
        </div>
    </div>
@endsection