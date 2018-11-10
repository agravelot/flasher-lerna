<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="has-navbar-fixed-top">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body>

@include('layouts.partials._navbar')

<div class="container has-margin-top-md">
    @include('layouts.partials._impersonating')
    @yield('content')
</div>

{{--@include('layouts.partials._footer')--}}

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
@yield('js')
</body>
</html>
