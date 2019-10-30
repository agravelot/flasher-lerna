<div class="navbar-item has-dropdown is-hoverable">
    <a class="navbar-link {{ Request::is('albums*') ? 'is-active' : '' }}" href="{{ route('albums.index') }}">
        <span>{{ __('Albums') }}</span>
    </a>

    <div class="navbar-dropdown is-boxed">
        <span class="navbar-item">
            {{ __('Show by') }}
        </span>
        <hr class="navbar-divider">
        <a class="navbar-item {{ Request::is('albums*') ? 'is-active' : '' }}" href="{{ route('albums.index') }}">
            {{ __('Albums') }}
        </a>
        <a class="navbar-item {{ Request::is('categories*') ? 'is-active' : '' }}" href="{{ route('categories.index') }}">
            {{ __('Categories') }}
        </a>
        <a class="navbar-item {{ Request::is('cosplayers*') ? 'is-active' : '' }}" href="{{ route('cosplayers.index') }}">
            {{ __('Cosplayer') }}
        </a>
    </div>
</div>
