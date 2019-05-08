@extends('layouts.app')

@section('pageTitle', __('Admin'))

@section('content')
    <div id="app">
        <div class="columns">
            <sidebar></sidebar>
            <div class="column">
                @include('layouts.partials._messages')
                <router-view></router-view>
                @yield('admin-content')
            </div>
        </div>
    </div>
@endsection
