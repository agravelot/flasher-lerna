@extends('layouts.app')

@section('content')
    <div class="columns">
        <div class="column is-2">
            @include('admin.partials._menu')
        </div>
        <div class="column is-10">
            @include('layouts.partials._messages')
            @yield('admin-content')
        </div>
    </div>
@endsection
