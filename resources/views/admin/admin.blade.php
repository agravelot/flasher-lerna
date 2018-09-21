@extends('layouts.app')

@section('content')
    <div class="columns">
        @include('admin.partials._menu')
        <div class="column is-10">
            @include('layouts.partials._messages')
            @yield('admin-content')
        </div>
    </div>
@endsection
