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
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div id="app">
    <nav class="navbar has-shadow">
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


                    @guest()
                        <a class="navbar-item" href="{{ route('posts.index') }}">Posts</a>
                        <a class="navbar-item" href="{{ route('albums.index') }}">Albums</a>
                    @else
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link" href="{{ route('posts.index') }}">
                                Posts
                            </a>
                            <div class="navbar-dropdown is-boxed is-right">
                                <a class="navbar-item" href="{{ route('posts.create') }}">
                                    Add
                                </a>
                            </div>
                        </div>

                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link" href="{{ route('albums.index') }}">
                                Albums
                            </a>
                            <div class="navbar-dropdown is-boxed is-right">
                                <a class="navbar-item" href="{{ route('albums.create') }}">
                                    Add
                                </a>
                            </div>
                        </div>
                    @endguest

                    <div class="navbar-item has-dropdown is-hoverable">
                        <a class="navbar-link" href="{{ route('goldenbook.index') }}">
                            Golden book
                        </a>

                        <div class="navbar-dropdown is-boxed is-right">
                            <a class="navbar-item" href="{{ route('goldenbook.create') }}">
                                Add
                            </a>
                        </div>
                    </div>


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
                    <a class="navbar-item" href="#">About</a>
                    <a class="navbar-item" href="#">Contact</a>
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
                    @endguest
                </div>
            </div>
        </div>
    </nav>
    @yield('content')
</div>

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
