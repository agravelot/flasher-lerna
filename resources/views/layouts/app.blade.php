@section('js')
    {{--<script src="{{ mix('/js/manifest.js') }}"></script>--}}
    {{--<script src="{{ mix('/js/vendor.js') }}"></script>--}}
    <script src="{{ mix('/js/app.js') }}"></script>
@endsection

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('pageTitle')
        - {{ settings()->has('app_name') ? settings()->get('app_name'): config('app.name', 'Flasher') }}</title>

    <meta name="description" content="@yield('seo_description', settings()->get('seo_description'))"/>

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    @yield('head')
    {!! Analytics::render() !!}
</head>

<body>
@include('layouts.partials._navbar')

@include('layouts.partials._impersonating')
@yield('content')

@include('layouts.partials._footer')

<!-- Scripts -->
@yield('js')
<!-- END Scripts -->

</body>
</html>

