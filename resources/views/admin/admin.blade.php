@extends('layouts.app')

@section('pageTitle', __('Admin'))

@section('content')
    <div id="app">
        <div class="columns">
            <sidebar></sidebar>
            <div class="column">
                <router-view></router-view>
            </div>
        </div>
    </div>

    <div class="columns">
        @include('admin.partials._menu')
        <div class="column" id="main-admin">
            @include('layouts.partials._messages')
            @yield('admin-content')
        </div>
    </div>
@endsection
