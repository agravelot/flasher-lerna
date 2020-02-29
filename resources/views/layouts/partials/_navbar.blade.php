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
                @include('layouts.partials._navbar_socials', ['class' => 'button is-black'])
            </div>

            <div class="level-item">
                <nav class="content has-text-centered is-hidden-mobile" id="navbar">
                    <div class="columns is-centered">
                        <div class="column is-narrow is-hidden-on-search">
                            <a class="button is-black is-uppercase {{ Request::is('/') ? 'is-active' : '' }}"
                               href="{{ url('/') }}">
                                {{ __('Home') }}
                            </a>
                        </div>
                        <div class="column is-narrow is-hidden-on-search">
                            <div class="dropdown is-hoverable">
                                <div class="dropdown-trigger">
                                    <button
                                        class="button is-black {{ Request::is('albums*') || Request::is('categories*') || Request::is('cosplayers*') ? 'is-active' : '' }}"
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
                                            {{ __('Cosplayers') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="column is-narrow is-hidden-on-search">
                            <a class="button is-black is-uppercase {{ Request::is('testimonials*') ? 'is-active' : '' }}"
                               href="{{ route('testimonials.index') }}">{{ __('Testimonial') }}</a>
                        </div>
                        <div class="column is-narrow is-hidden-on-search">
                            {{--<a class="navbar-item {{ Request::is('about') ? 'is-active' : '' }}" href="#">{{ __('About') }}</a>--}}
                            <a class="button is-black is-uppercase {{ Request::is('contact*') ? 'is-active' : '' }}"
                               href="{{ route('contact.index') }}">{{ __('Contact') }}</a>
                        </div>
                        <div class="column is-narrow">
                            @include('layouts.partials._search')
                            <button class="button is-black">
                            <span class="icon has-text-white"
                                  onclick="document.getElementById('aa-search-input').style.display = 'block';document.getElementById('aa-search-input').select()">
                                @fa('search')
                            </span>
                            </button>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="level-right">
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
                        <span
                            class="is-uppercase has-text-grey-light has-text-weight-light is-hidden-touch">
                            {{ __('Login') }}
                        </span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
