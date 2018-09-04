<nav class="navbar has-shadow">
    <div class="container">
        <div class="navbar-brand">
            <a href="{{ url('/') }}" class="navbar-item">{{ config('app.name', 'PicBlog') }}</a>
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
                <a class="navbar-item" href="{{ route('goldenbook.index') }}">Golden book</a>
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
                <a class="navbar-item" href="{{ route('contact.create') }}">Contact</a>
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
                            <a class="navbar-item" href="{{ route('dashboard') }}">
                                Admin
                            </a>
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
                                @csrf
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</nav>