<nav class="navbar is-black" role="navigation" aria-label="dropdown navigation">
    <div class="container">
        <div class="navbar-brand">

            <a class="navbar-item" href="/">
                <img src="{{ asset('/svg/logo.svg') }}"
                     alt="{{ __('Logo of') }} {{ settings()->get('app_name', config('app.name', 'Flasher')) }}">
            </a>

            @include('layouts.partials._navbar_socials', ['class' => 'navbar-item'])

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
                <a class="navbar-item {{ Request::is('testimonials*') ? 'is-active' : '' }}"
                   href="{{ route('testimonials.index') }}">{{ __('Testimonial') }}</a>
                {{--<a class="navbar-item {{ Request::is('about') ? 'is-active' : '' }}" href="#">{{ __('About') }}</a>--}}
                <a class="navbar-item {{ Request::is('contact*') ? 'is-active' : '' }}"
                   href="{{ route('contact.index') }}">{{ __('Contact') }}</a>
                @auth()
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link" @can('dashboard') href="{{ route('admin.dashboard') }}" @endcan>
                            <span class="icon">
                                @fas('user')
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                        </a>

                        <div class="navbar-dropdown is-boxed is-right">

                            @can('dashboard')
                                <a class="navbar-item {{ Request::is('admin*') ? 'is-active' : '' }}"
                                   href="{{ route('admin.dashboard') }}">
                                    {{ __('Admin') }}
                                </a>
                            @endcan
                            <a class="navbar-item {{ Request::is('profile/my-albums*') ? 'is-active' : '' }}"
                               href="{{ route('profile.my-albums') }}">
                                {{ __('My albums') }}
                            </a>
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
                @else
                    <a class="navbar-item" href="{{ route('login') }}">
                        <span class="icon">
                            @fas('sign-in-alt')
                        </span>
                        <span>{{ __('Login') }}</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
