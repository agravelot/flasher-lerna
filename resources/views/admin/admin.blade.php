@extends('layouts.app')

@section('content')
    <div class="columns is-fullheight">
        @include('admin.partials._menu_list')
        <div class="column is-main-content">
            @include('layouts.partials._messages')
            @yield('admin-content')
        </div>
    </div>
@endsection
