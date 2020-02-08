<div class="level has-margin-top-md">
    <div class="level-left">
        @include('layouts.partials._navbar_socials', ['class' => 'button is-black'])
    </div>

    <div class="level-right">
        @include('layouts.partials._search')
        @auth()
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link" @can('dashboard') href="{{ route('admin.dashboard') }}" @endcan>
                    <span class="icon">
                        @fas('user')
                    </span>
                </a>

                <div class="navbar-dropdown is-boxed is-right">
                    <span class="navbar-item has-text-weight-bold">
                        {{ Auth::user()->name }}
                    </span>
                    <hr class="navbar-divider">
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
            <a class="button is-black" href="{{ route('login') }}">
                <span class="icon">
                    @fas('sign-in-alt')
                </span>
                <span>{{ __('Login') }}</span>
            </a>
        @endauth
    </div>
</div>
