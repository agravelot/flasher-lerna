@extends('layouts.app')

@section('content')
    <div class="columns is-fullheight ">
        <div class="column is-one-quarter is-sidebar-menu is-hidden-mobile">
            @include('admin.partials._menu')
        </div>
        <div class="column has-padding-lg">
            @include('layouts.partials._messages')
            @yield('admin-content')
        </div>
    </div>
@endsection
