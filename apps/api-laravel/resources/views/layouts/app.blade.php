@section('js')
    {{--<script src="{{ mix('/js/manifest.js') }}"></script>--}}
    {{--<script src="{{ mix('/js/vendor.js') }}"></script>--}}
    <script src="{{ mix('js/app.js') }}"></script>
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
            @yield('pageTitle') - {{ __('Photographer') }} | {{ settings()->get('app_name') }}
        @else
            {{ settings()->get('default_page_title') }}
        @endif
    </title>

    <meta name="description" content="@yield('seo_description', settings()->get('seo_description'))"/>

    <link rel="preconnect" href="{{ config('medialibrary.s3.domain') }}" crossorigin>
    <link rel="dns-prefetch" href="{{ config('medialibrary.s3.domain') }}">

    @include('layouts.partials._favicon')

    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
    @yield('head')
    {!! Analytics::render() !!}

    {!! new \App\Http\SchemasOrg\HomepageSchema() !!}
</head>

<body>
<header>
    @include('layouts.partials._navbar')
    @include('layouts.partials._impersonating')
</header>

<main>
    @if(! request()->is('admin*') && ! request()->is('/'))
        <div class="hero is-black is-radiusless">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title">@yield('pageTitle')</h1>
                    @yield('headerTags')
                </div>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<footer>
    @include('layouts.partials._footer')
</footer>

<!-- Scripts -->
@yield('js')
@if (config('crisp.enabled'))
    @include('layouts.partials._crisp')
@endif
<!-- END Scripts -->

</body>
</html>

