@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card-content">
            @include('cosplayers._feilds', [ 'route' => route('cosplayers.store') ])
        </div>
    </div>
@endsection
