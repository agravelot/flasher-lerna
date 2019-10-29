<a class="navbar-item {{ Request::is('albums*') ? 'is-active' : '' }}"
   href="{{ route('albums.index') }}">{{ __('Galery') }}</a>
