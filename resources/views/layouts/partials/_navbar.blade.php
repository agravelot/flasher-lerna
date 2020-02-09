<div class="has-background-black">
    <div class="container">

        <div class="level is-mobile">
            <div class="level-left">
                <button role="button"
                   class="navbar-burger is-hidden-tablet has-text-white"
                   aria-label="menu"
                   aria-expanded="false"
                   data-target="navbar">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </button>
                @include('layouts.partials._navbar_socials', ['class' => 'button is-black is-hidden-on-search'])
                @include('layouts.partials._search')
            </div>

            <div class="level-right">
                <button class="button is-black">
                    <span class="icon has-text-white" onclick="document.getElementById('aa-search-input').select()">
                        @fa('search')
                    </span>
                </button>

                @auth()
                    <div class="dropdown is-right is-boxed is-hoverable">
                        <div class="dropdown-trigger">
                            <button class="button is-black" aria-haspopup="true" aria-controls="dropdown-menu4">
                                <span class="is-hidden-touch">{{ Auth::user()->name }}</span>
                                <span class="icon is-small">
                                    @fas('user')
                                </span>
                            </button>
                        </div>
                        <div class="dropdown-menu" id="dropdown-menu4" role="menu">
                            <div class="dropdown-content">
                                <div class="dropdown-item">
                                    <span class="has-text-weight-bold">{{ Auth::user()->name }}</span>
                                </div>
                                <hr class="dropdown-divider">
                                @can('dashboard')
                                    <a class="dropdown-item {{ Request::is('admin*') ? 'is-active' : '' }}"
                                       href="{{ route('admin.dashboard') }}">
                                        {{ __('Admin') }}
                                    </a>
                                @endcan
                                <a class="dropdown-item {{ Request::is('profile/my-albums*') ? 'is-active' : '' }}"
                                   href="{{ route('profile.my-albums') }}">
                                    {{ __('My albums') }}
                                </a>
                                <hr class="dropdown-divider">
                                <a class="dropdown-item has-text-danger"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a class="button is-black" href="{{ route('login') }}">
                        <span class="icon">
                            @fas('sign-in-alt')
                        </span>
                        <span class="is-uppercase has-text-grey-light has-text-weight-light">{{ __('Login') }}</span>
                    </a>
                @endauth
            </div>
        </div>

        <div class="level">
            <div class="level-item">
                <a class="" href="/">
                    <div class="image is-96x96">
                        <img src="{{ asset('/svg/logo.svg') }}"
                             alt="{{ __('Logo of') }} {{ settings()->get('app_name', config('app.name', 'Flasher')) }}">
                    </div>
                </a>

            </div>
        </div>
    </div>

    <nav class="content has-text-centered is-hidden-mobile has-margin-top-md" id="navbar">
        <div class="columns is-centered">
            <div class="column is-narrow">
                <a class="button is-black is-uppercase {{ Request::is('/') ? 'is-active' : '' }}"
                   href="{{ url('/') }}">{{ __('Home') }}</a>
            </div>
            <div class="column is-narrow">
                <div class="dropdown is-hoverable">
                    <div class="dropdown-trigger">
                        <button class="button is-black {{ Request::is('albums*') ? 'is-active' : '' }}"
                                aria-haspopup="true"
                                aria-controls="dropdown-menu4">
                            <span class="is-uppercase">{{ __('Gallery') }}</span>
                            <span class="icon is-small">
                                @fa('angle-down')
                            </span>
                        </button>
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu4" role="menu">
                        <div class="dropdown-content">
                            <a class="dropdown-item is-uppercase {{ Request::is('albums*') ? 'is-active' : '' }}"
                               href="{{ route('albums.index') }}">
                                {{ __('Albums') }}
                            </a>
                            <a class="dropdown-item is-uppercase {{ Request::is('categories*') ? 'is-active' : '' }}"
                               href="{{ route('categories.index') }}">
                                {{ __('Categories') }}
                            </a>
                            <a class="dropdown-item is-uppercase {{ Request::is('cosplayers*') ? 'is-active' : '' }}"
                               href="{{ route('cosplayers.index') }}">
                                {{ __('Cosplayer') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-narrow">
                <a class="button is-black is-uppercase {{ Request::is('testimonials*') ? 'is-active' : '' }}"
                   href="{{ route('testimonials.index') }}">{{ __('Testimonial') }}</a>
            </div>
            <div class="column is-narrow">
                {{--<a class="navbar-item {{ Request::is('about') ? 'is-active' : '' }}" href="#">{{ __('About') }}</a>--}}
                <a class="button is-black is-uppercase {{ Request::is('contact*') ? 'is-active' : '' }}"
                   href="{{ route('contact.index') }}">{{ __('Contact') }}</a>
            </div>
        </div>
    </nav>
</div>
