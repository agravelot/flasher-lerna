<nav class="has-background-black" id="return-to-top-helper">
    <div class="container">

        @include('layouts.partials._topbar')

        <div class="level">
            <div class="level-item">
                <figure class="image is-128x128">
                    <a class="" href="/">
                        <img src="{{ asset('/svg/logo.svg') }}"
                             alt="{{ __('Logo of') }} {{ settings()->get('app_name', config('app.name', 'Flasher')) }}">
                    </a>
                    <div class="has-text-centered has-margin-top-sm">
                        <span class="has-text-white">Jkanda</span>
                    </div>
                </figure>
            </div>
        </div>

        <div class="level">
            <div class="level-item has-margin-bottom-lg">
                <a class="button is-black {{ Request::is('/') ? 'is-active' : '' }}"
                   href="{{ url('/') }}">{{ __('Home') }}</a>

                <div class="dropdown is-hoverable">
                    <div class="dropdown-trigger">
                        <a class="button is-black {{ Request::is('albums*') ? 'is-active' : '' }}"
                           href="{{ route('albums.index') }}"
                           aria-haspopup="true"
                           aria-controls="dropdown-menu4">
                            <span>{{ __('Galery') }}</span>
                            <span class="icon is-small">
                                @fa('angle-down')
                            </span>
                        </a>

                    </div>
                    <div class="dropdown-menu" id="dropdown-menu4" role="menu">
                        <div class="dropdown-content">
                            <a class="dropdown-item {{ Request::is('albums*') ? 'is-active' : '' }}"
                               href="{{ route('albums.index') }}">
                                {{ __('Albums') }}
                            </a>
                            <a class="dropdown-item {{ Request::is('categories*') ? 'is-active' : '' }}"
                               href="{{ route('categories.index') }}">
                                {{ __('Categories') }}
                            </a>
                            <a class="dropdown-item {{ Request::is('cosplayers*') ? 'is-active' : '' }}"
                               href="{{ route('cosplayers.index') }}">
                                {{ __('Cosplayer') }}
                            </a>
                        </div>
                    </div>
                </div>

                <a class="button is-black {{ Request::is('testimonials*') ? 'is-active' : '' }}"
                   href="{{ route('testimonials.index') }}">{{ __('Testimonial') }}</a>
                {{--<a class="navbar-item {{ Request::is('about') ? 'is-active' : '' }}" href="#">{{ __('About') }}</a>--}}
                <a class="button is-black {{ Request::is('contact*') ? 'is-active' : '' }}"
                   href="{{ route('contact.index') }}">{{ __('Contact') }}</a>
            </div>
        </div>
    </div>
</nav>
