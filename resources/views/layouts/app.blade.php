<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('pageTitle') - {{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    @yield('head')
    {!! Analytics::render() !!}
</head>
<body>

@include('layouts.partials._navbar')

<div class="has-margin-md">
    @include('layouts.partials._impersonating')
    @yield('content')
</div>

@include('layouts.partials._footer')

<!-- Scripts -->
<script src="{{ mix('/js/manifest.js') }}"></script>
<script src="{{ mix('/js/vendor.js') }}"></script>
<script src="{{ mix('/js/app.js') }}"></script>
@yield('js')

</body>
</html>
