<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="has-navbar-fixed-top">
<div id="app">
    <nav class="navbar has-shadow is-fixed-top">
        <div class="container">
            <div class="navbar-brand">
                <a href="{{ url('/') }}" class="navbar-item">{{ config('app.name', 'Laravel') }}</a>
            </div>


            <div class="navbar-burger burger" data-target="navMenu">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="navbar-menu" id="navMenu">
                <div class="navbar-start">
                    <a class="navbar-item" href="{{ route('posts.index') }}">Posts</a>
                    <a class="navbar-item" href="{{ route('albums.index') }}">Albums</a>
                    <a class="navbar-item" href="#">A propos</a>
                    <a class="navbar-item" href="#">Contact</a>

                </div>

                <a class="navbar-item" href="#" target="_blank">
                      <span class="icon">
                            <i class="fab fa-facebook-f" aria-hidden="true"></i>
                      </span>
                </a>
                <a class="navbar-item" href="#" target="_blank">
                      <span class="icon">
                            <i class="fab fa-instagram" aria-hidden="true"></i>
                      </span>
                </a>


                <div class="navbar-end">
                    @guest()
                        <a class="navbar-item " href="{{ route('login') }}">Login</a>
                        <a class="navbar-item " href="{{ route('register') }}">Register</a>
                    @else
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link" href="#">
                                <span class="icon">
                                  <i class="fas fa-user"></i>
                                </span>
                                <span>{{ Auth::user()->name }}</span>
                            </a>

                            <div class="navbar-dropdown is-boxed is-right">
                                <a class="navbar-item" href="#">
                                    Settings
                                </a>
                                <hr class="navbar-divider">
                                <a class="navbar-item has-text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>
    @yield('content')
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
