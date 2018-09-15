@extends('layouts.app')

@section('content')
    {{--<div class="container">--}}
    <div class="columns">

        @include('admin.partials._menu_list')

        <div class="column is-9">

            @include('layouts.partials._messages')

            @include('admin.albums._feilds', ['route' => route('albums.index') . '/' .  $album->id])
        </div>
    </div>
    {{--</div>--}}
@endsection
