<div class="column is-one-fifth" id="admin-menu" style="transition: all 0.6s cubic-bezier(0.945, 0.020, 0.270, 0.665);
    transform-origin: bottom left;">
    <aside class="menu">
        <ul class="menu-list">
            <p class="menu-label">
                {{ __('Administration') }}
            </p>
            <li>
                <a href="{{ route('admin.dashboard') }}" {{ Request::is('admin') ? 'class=is-active' : '' }}>
                <span class="icon">
                    <i class="fas fa-home"></i>
                </span>
                    {{ __('Dashboard') }}
                </a>
            </li>
            @can('index', \App\Models\Album::class)
                <li>
                    <a href="{{ route('admin.albums.index') }}" {{ Request::is('admin/albums*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        <i class="fas fa-images"></i>
                    </span>
                        {{ __('Albums') }}
                    </a>
                </li>
            @endcan
            @can('index', \App\Models\Category::class)
                <li>
                    <a href="{{ route('admin.categories.index') }}" {{ Request::is('admin/categories*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        <i class="fas fa-tags"></i>
                    </span>
                        {{ __('Categories') }}
                    </a>
                </li>
            @endcan
            @can('index', \App\Models\Cosplayer::class)
                <li>
                    <a href="{{ route('admin.cosplayers.index') }}" {{ Request::is('admin/cosplayers*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        <i class="fas fa-user-tag"></i>
                    </span>
                        {{ __('Cosplayers') }}
                    </a>
                </li>
            @endcan
            @can('index', \App\Models\User::class)
                <li>
                    <a href="{{ route('admin.users.index') }}" {{ Request::is('admin/users*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        <i class="fas fa-user"></i>
                    </span>
                        {{ __('Users') }}
                    </a>
                </li>
            @endcan
            @can('index', \App\Models\Contact::class)
                <li>
                    <a href="{{ route('admin.contacts.index') }}" {{ Request::is('admin/contacts*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        <i class="fas fa-pen-fancy"></i>
                    </span>
                        {{ __('Contacts') }}
                    </a>
                </li>
            @endcan
            @can('index', \App\Models\GoldenBookPost::class)
                <li>
                    <a href="{{ route('admin.goldenbook.index') }}" {{ Request::is('admin/goldenbook*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        <i class="fas fa-book"></i>
                    </span>
                        {{ __('Golden book') }}
                    </a>
                </li>
            @endcan
        </ul>


        <p class="menu-label">
            {{ __('Monitoring') }}
        </p>
        <ul class="menu-list">
            <li>
                <a>
                <span class="icon">
                    <i class="fas fa-tachometer-alt"></i>
                </span>
                    {{ __('Statistics') }}
                </a>
            </li>
            <li>
                <a href="{{ route('horizon.index') }}" {{ Request::is('horizon') ? 'class=is-active' : '' }}>
                <span class="icon">
                    <i class="fas fa-tasks"></i>
                </span>
                    {{ __('Background tasks') }}
                </a>
            </li>
        </ul>

        <ul class="menu-list">
            <li>
                <a class="has-text-info has-margin-top-lg" href="{{ route('home') }}">
                <span class="icon">
                    <i class="fas fa-chevron-circle-left"></i>
                </span>
                    {{ __('Back to the website') }}
                </a>
            </li>
        </ul>
    </aside>
</div>

<div class="admin-collapse-button">
    <a class="has-text-info has-margin-top-lg" id="collapse-admin-menu">
        <span class="icon">
            <i class="fas fa-chevron-circle-left"></i>
        </span>
        {{ __('Collapse') }}
    </a>
</div>

@section('js')
    <script src="{{ mix('js/admin.js') }}"></script>
@stop