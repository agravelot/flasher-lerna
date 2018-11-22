<nav class="navbar is-fixed-top has-shadow is-transparent" role="navigation" aria-label="dropdown navigation">
    <div class="container">
        <div class="navbar-brand">
            <a href="{{ url('/') }}" class="navbar-item">{{ config('app.name', 'PicBlog') }}</a>

            @if (env('FACEBOOK_URL'))
                <a class="navbar-item is-hidden-desktop" href="{{ env('FACEBOOK_URL') }}" target="_blank">
                    <span class="icon">
                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                    </span>
                </a>
            @endif

            @if (env('INSTAGRAM_URL'))
                <a class="navbar-item is-hidden-desktop" href="{{ env('INSTAGRAM_URL') }}" target="_blank">
                    <span class="icon">
                        <i class="fab fa-instagram" aria-hidden="true"></i>
                    </span>
                </a>
            @endif
            <a role="button" class="navbar-burger burger" aria-label="menu" data-target="navMenu" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </a>
        </div>


        <div class="navbar-menu" id="navMenu">
            <div class="navbar-start">
                {{--                <a class="navbar-item {{ Request::is('posts*') ? 'is-active' : '' }}" href="{{ route('posts.index') }}">Posts</a>--}}
                <a class="navbar-item {{ Request::is('albums*') ? 'is-active' : '' }}"
                   href="{{ route('albums.index') }}">Albums</a>
                <a class="navbar-item {{ Request::is('categories*') ? 'is-active' : '' }}"
                   href="{{ route('categories.index') }}">Categories</a>
                <a class="navbar-item {{ Request::is('goldenbook*') ? 'is-active' : '' }}"
                   href="{{ route('goldenbook.index') }}">Golden book</a>
            </div>

            @if (env('FACEBOOK_URL'))
                <a class="navbar-item is-hidden-touch" href="{{ env('FACEBOOK_URL') }}" target="_blank">
                    <span class="icon">
                        <i class="fab fa-facebook-f" aria-hidden="true"></i>
                    </span>
                </a>
            @endif

            @if (env('INSTAGRAM_URL'))
                <a class="navbar-item is-hidden-touch" href="{{ env('INSTAGRAM_URL') }}" target="_blank">
                <span class="icon">
                    <i class="fab fa-instagram" aria-hidden="true"></i>
                </span>
                </a>
            @endif

            <div class="navbar-end">
                <a class="navbar-item {{ Request::is('about') ? 'is-active' : '' }}" href="#">About</a>
                <a class="navbar-item {{ Request::is('contact*') ? 'is-active' : '' }}"
                   href="{{ route('contact.create') }}">Contact</a>
                @guest()
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link" href="#">
                            <span class="icon">
                                <i class="fas fa-globe-africa"></i>
                            </span>
                            <span>FR</span>
                        </a>

                        <div class="navbar-dropdown is-boxed is-right">
                            <a class="navbar-item" href="#">
                                FR
                            </a>
                            <a class="navbar-item" href="#">
                                EN
                            </a>
                        </div>
                    </div>

                @else
                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link" href="#">
                            <span class="icon">
                                <i class="fas fa-user"></i>
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                        </a>

                        <div class="navbar-dropdown is-boxed is-right">

                            @can('dashboard')
                                <a class="navbar-item  {{ Request::is('admin*') ? 'is-active' : '' }} "
                                   href="{{ route('admin.dashboard') }}">
                                    Admin
                                </a>
                            @endcan
                            <a class="navbar-item {{ Request::is('') ? 'is-active' : '' }}" href="#">
                                Settings
                            </a>
                            <hr class="navbar-divider">
                            <a class="navbar-item has-text-danger" href="{{ route('logout') }}"
                               onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                Logout
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