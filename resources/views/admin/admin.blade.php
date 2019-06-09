@extends('layouts.app')

@section('pageTitle', __('Admin'))

@section('content')
    <section class="section" id="app">
        <div class="columns">
            <sidebar></sidebar>
            <div class="column">
                @include('layouts.partials._messages')
                <router-view></router-view>
                @yield('admin-content')
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{ mix('/js/main/admin.js') }}"></script>
@endsection
