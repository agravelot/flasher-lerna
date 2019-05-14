<section class="hero is-small is-primary">
    <!-- Hero head: will stick at the top -->
    <div class="hero-head">
        <nav class="navbar" role="navigation" aria-label="dropdown navigation">
            <div class="container">
                <div class="navbar-brand">

                    <a href="{{ url('/') }}"
                       class="navbar-item">{{ settings()->get('app_name', config('app.name', 'Flasher')) }}</a>

                    @include('layouts.partials._navbar_socials')

                    <a role="button" class="navbar-burger burger" aria-label="menu" data-target="navMenu"
                       aria-expanded="false">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div>
                <div id="navMenu" class="navbar-menu">
                    <div class="navbar-end">
                        <a class="navbar-item {{ Request::is('/') ? 'is-active' : '' }}"
                           href="{{ url('/') }}">{{ __('Home') }}</a>
                        @include('layouts.partials._navbar_albums')
                        @include('layouts.partials._navbar_categories')
                        <a class="navbar-item {{ Request::is('goldenbook*') ? 'is-active' : '' }}"
                           href="{{ route('goldenbook.index') }}">{{ __('Golden book') }}</a>
                        {{--                <a class="navbar-item {{ Request::is('about') ? 'is-active' : '' }}" href="#">{{ __('About') }}</a>--}}
                        <a class="navbar-item {{ Request::is('contact*') ? 'is-active' : '' }}"
                           href="{{ route('contact.index') }}">{{ __('Contact') }}</a>
                        @auth()
                            <div class="navbar-item has-dropdown is-hoverable">
                                <a class="navbar-link" href="{{ route('admin.dashboard') }}">
                            <span class="icon">
                                <i class="fas fa-user"></i>
                            </span>
                                    <span>{{ Auth::user()->name }}</span>
                                </a>

                                <div class="navbar-dropdown is-boxed is-right">

                                    @can('dashboard')
                                        <a class="navbar-item  {{ Request::is('admin*') ? 'is-active' : '' }} "
                                           href="{{ route('admin.dashboard') }}">
                                            {{ __('Admin') }}
                                        </a>
                                    @endcan
                                    <hr class="navbar-divider">
                                    <a class="navbar-item has-text-danger"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                          style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </div>
                        @endguest
                        {{--                        <span class="navbar-item">--}}
                        {{--                          <a class="button is-primary is-inverted">--}}
                        {{--                            <span class="icon">--}}
                        {{--                              <i class="fab fa-github"></i>--}}
                        {{--                            </span>--}}
                        {{--                            <span>Download</span>--}}
                        {{--                          </a>--}}
                        {{--                        </span>--}}
                    </div>
                </div>
            </div>
        </nav>
    </div>

@if(! request()->is('admin*'))
    <!-- Hero content: will be in the middle -->
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title">
                    @yield('pageTitle')
                </h1>
                {{--            <h2 class="subtitle">--}}
                {{--                Subtitle--}}
                {{--            </h2>--}}
            </div>
        </div>
@endif

<!-- Hero footer: will stick at the bottom -->
    {{--    <div class="hero-foot">--}}
    {{--        <nav class="tabs">--}}
    {{--            <div class="container">--}}
    {{--                <ul>--}}
    {{--                    <li class="is-active"><a>Overview</a></li>--}}
    {{--                    <li><a>Modifiers</a></li>--}}
    {{--                    <li><a>Grid</a></li>--}}
    {{--                    <li><a>Elements</a></li>--}}
    {{--                    <li><a>Components</a></li>--}}
    {{--                    <li><a>Layout</a></li>--}}
    {{--                </ul>--}}
    {{--            </div>--}}
    {{--        </nav>--}}
    {{--    </div>--}}
</section>

{{--<nav class="navbar" role="navigation" aria-label="dropdown navigation">--}}
{{--    <div class="container">--}}
{{--        <div class="navbar-brand">--}}
{{--            <a href="{{ url('/') }}" class="navbar-item">{{ settings()->get('app_name', config('app.name', 'Flasher')) }}</a>--}}

{{--            @include('layouts.partials._navbar_socials')--}}

{{--            <a role="button" class="navbar-burger burger" aria-label="menu" data-target="navMenu" aria-expanded="false">--}}
{{--                <span></span>--}}
{{--                <span></span>--}}
{{--                <span></span>--}}
{{--            </a>--}}
{{--        </div>--}}

{{--        <div class="navbar-menu" id="navMenu">--}}
{{--            <div class="navbar-end">--}}
{{--                <a class="navbar-item {{ Request::is('/') ? 'is-active' : '' }}"--}}
{{--                   href="{{ url('/') }}">{{ __('Home') }}</a>--}}
{{--                @include('layouts.partials._navbar_categories')--}}
{{--                <a class="navbar-item {{ Request::is('goldenbook*') ? 'is-active' : '' }}"--}}
{{--                   href="{{ route('goldenbook.index') }}">{{ __('Golden book') }}</a>--}}
{{--                <a class="navbar-item {{ Request::is('about') ? 'is-active' : '' }}" href="#">{{ __('About') }}</a>--}}
{{--                <a class="navbar-item {{ Request::is('contact*') ? 'is-active' : '' }}"--}}
{{--                   href="{{ route('contact.index') }}">{{ __('Contact') }}</a>--}}
{{--                @auth()--}}
{{--                    <div class="navbar-item has-dropdown is-hoverable">--}}
{{--                        <a class="navbar-link" href="{{ route('admin.dashboard') }}">--}}
{{--                            <span class="icon">--}}
{{--                                <i class="fas fa-user"></i>--}}
{{--                            </span>--}}
{{--                            <span>{{ Auth::user()->name }}</span>--}}
{{--                        </a>--}}

{{--                        <div class="navbar-dropdown is-boxed is-right">--}}

{{--                            @can('dashboard')--}}
{{--                                <a class="navbar-item  {{ Request::is('admin*') ? 'is-active' : '' }} "--}}
{{--                                   href="{{ route('admin.dashboard') }}">--}}
{{--                                    {{ __('Admin') }}--}}
{{--                                </a>--}}
{{--                            @endcan--}}
{{--                            <hr class="navbar-divider">--}}
{{--                            <a class="navbar-item has-text-danger"--}}
{{--                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">--}}
{{--                                {{ __('Logout') }}--}}
{{--                            </a>--}}

{{--                            <form id="logout-form" action="{{ route('logout') }}" method="POST"--}}
{{--                                  style="display: none;">--}}
{{--                                @csrf--}}
{{--                            </form>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endguest--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</nav>--}}