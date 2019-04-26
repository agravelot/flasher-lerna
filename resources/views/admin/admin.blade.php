@extends('layouts.app')

@section('pageTitle', __('Admin'))

@section('content')
    <div id="app">
        <div class="columns">
            @include('admin.partials._menu')
            <div class="column" id="main-admin">
                @include('layouts.partials._messages')
                <app></app>
                @yield('admin-content')
            </div>
        </div>
    </div>
@endsection
