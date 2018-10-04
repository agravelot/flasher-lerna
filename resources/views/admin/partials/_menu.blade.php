<aside class="menu">
    <p class="menu-label">
        Administration
    </p>
    <ul class="menu-list">
        <li>
            <a href="{{ route('admin.dashboard') }}" {{ Request::is('admin') ? 'class=is-active' : '' }}>
                <span class="icon">
                    <i class="fas fa-home"></i>
                </span>
                Dashboard
            </a>
        </li>
        @can('index', \App\Models\Album::class)
            <li>
                <a href="{{ route('admin.albums.index') }}" {{ Request::is('admin/albums*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        <i class="fas fa-images"></i>
                    </span>
                    Albums
                </a>
            </li>
        @endcan
        @can('index', \App\Models\Contact::class)
            <li>
                <a href="{{ route('admin.contacts.index') }}" {{ Request::is('admin/contacts*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        <i class="fas fa-pen-fancy"></i>
                    </span>
                    Contacts
                </a>
            </li>
        @endcan
        @can('index', \App\Models\Cosplayer::class)
            <li>
                <a href="{{ route('admin.cosplayers.index') }}" {{ Request::is('admin/cosplayers*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        <i class="fas fa-user-tag"></i>
                    </span>
                    Cosplayers
                </a>
            </li>
        @endcan
        @can('index', \App\Models\User::class)
            <li>
                <a href="{{ route('admin.users.index') }}" {{ Request::is('admin/users*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        <i class="fas fa-user"></i>
                    </span>
                    Users
                </a>
            </li>
        @endcan
    </ul>

    <a href="{{ route('home') }}">
        <span class="icon">
            <i class="fas fa-chevron-circle-left"></i>
        </span>
        Retour sur le site
    </a>
</aside>