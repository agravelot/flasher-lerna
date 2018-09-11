@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card-content">
            @include('albums._feilds', ['route' => route('albums.index') . '/' .  $album->id])
        </div>
    </div>
@endsection