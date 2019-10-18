@section('js')
    {{--<script src="{{ mix('/js/main/manifest.js') }}"></script>--}}
    {{--<script src="{{ mix('/js/main/vendor.js') }}"></script>--}}
    <script src="{{ mix('/js/main/app.js') }}"></script>
@endsection

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head @yield('head_tag')>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @hasSection('pageTitle')
            @yield('pageTitle') - {{ settings()->get('app_name') }}
        @else
            {{ settings()->get('default_page_title') }}
        @endif
    </title>

    <meta name="description" content="@yield('seo_description', settings()->get('seo_description'))"/>

    <link rel="preconnect" href="{{ config('medialibrary.s3.domain') }}" crossorigin>
    <link rel="dns-prefetch" href="{{ config('medialibrary.s3.domain') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('img/favicon/site.webmanifest') }}">
    <link rel="shortcut icon" href="{{ asset('img/favicon/favicon.ico') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('img/favicon/mstile-144x144.png') }}">
    <meta name="msapplication-config" content="{{ asset('img/favicon/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    @yield('head')
    {!! Analytics::render() !!}
</head>

<body>
@include('layouts.partials._navbar')
@include('layouts.partials._impersonating')

@if(! request()->is('admin*') && ! request()->is('/'))
    <div class="hero is-black is-radiusless">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title">
                    @yield('pageTitle')
                </h1>
                @yield('headerTags')
            </div>
        </div>
    </div>
@endif

@yield('content')

@include('layouts.partials._footer')

<!-- Scripts -->
@yield('js')
<!-- END Scripts -->

</body>
</html>

