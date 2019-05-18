<a class="navbar-item {{ Request::is('categories*') ? 'is-active' : '' }}"
   href="{{ route('categories.index') }}">{{ __('Categories') }}</a>
