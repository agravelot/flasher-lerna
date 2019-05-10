<nav class="navbar" role="navigation" aria-label="dropdown navigation">
    <div class="container">
        <div class="navbar-brand">
            <a href="{{ url('/') }}" class="navbar-item">{{ settings()->has('app_name') ? settings()->get('app_name'): config('app.name', 'Flasher') }}</a>

            @include('layouts.partials._navbar_socials')

            <a role="button" class="navbar-burger burger" aria-label="menu" data-target="navMenu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </a>
        </div>

        <div class="navbar-menu" id="navMenu">
            <div class="navbar-start">
            </div>

            <div class="navbar-end">
                <a class="navbar-item {{ Request::is('/') ? 'is-active' : '' }}"
                   href="{{ url('/') }}">{{ __('Home') }}</a>
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
            </div>
        </div>
    </div>
</nav>