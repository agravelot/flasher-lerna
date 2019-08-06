<div class="column is-one-fifth" id="admin-menu">
    <aside class="menu">
        <ul class="menu-list has-margin-bottom-md">
            <li class="menu-label">
                {{ __('Administration') }}
            </li>
            <li>
                <a href="{{ route('admin.dashboard') }}" {{ Request::is('admin') ? 'class=is-active' : '' }}>
                <span class="icon">
                    @fas('home')
                </span>
                    {{ __('Dashboard') }}
                </a>
            </li>
{{--            @can('index', \App\Models\Album::class)--}}
{{--                <li>--}}
{{--                    <a href="{{ route('admin.albums.index') }}" {{ Request::is('admin/albums*') ? 'class=is-active' : '' }}>--}}
{{--                    <span class="icon">--}}
{{--                        @fas('images')--}}
{{--                    </span>--}}
{{--                        {{ __('Albums') }}--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--            @endcan--}}
            @can('index', \App\Models\Category::class)
                <li>
                    <a href="{{ route('admin.categories.index') }}" {{ Request::is('admin/categories*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        @fas('tags')
                    </span>
                        {{ __('Categories') }}
                    </a>
                </li>
            @endcan
            @can('index', \App\Models\Cosplayer::class)
                <li>
                    <a href="{{ route('admin.cosplayers.index') }}" {{ Request::is('admin/cosplayers*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        @fas('user-tag')
                    </span>
                        {{ __('Cosplayers') }}
                    </a>
                </li>
            @endcan
            @can('index', \App\Models\User::class)
                <li>
                    <a href="{{ route('admin.users.index') }}" {{ Request::is('admin/users*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        @fas('user')
                    </span>
                        {{ __('Users') }}
                    </a>
                </li>
            @endcan
            @can('index', \App\Models\Contact::class)
                <li>
                    <a href="{{ route('admin.contacts.index') }}" {{ Request::is('admin/contacts*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        @fas('pen-fancy')
                    </span>
                        {{ __('Contacts') }}
                    </a>
                </li>
            @endcan
            @can('index', \App\Models\GoldenBookPost::class)
                <li>
                    <a href="{{ route('admin.testimonials.index') }}" {{ Request::is('admin/testimonials*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        @fas('book')
                    </span>
                        {{ __('Golden book') }}
                    </a>
                </li>
            @endcan

            @can('index', \App\Models\SocialMedia::class)
                <li>
                    <a href="{{ route('admin.social-medias.index') }}" {{ Request::is('admin/social-medias*') ? 'class=is-active' : '' }}>
                    <span class="icon">
                        @fas('external-link-alt')
                    </span>
                        {{ __('Social medias') }}
                    </a>
                </li>
            @endcan
        </ul>

        <ul class="menu-list">
            <li class="menu-label">
                {{ __('Monitoring') }}
            </li>
            <li>
                <a>
                <span class="icon">
                    @fas('tachometer-alt')
                </span>
                    {{ __('Statistics') }}
                </a>
            </li>
            <li>
                <a href="{{ route('horizon.index') }}" {{ Request::is('horizon') ? 'class=is-active' : '' }}>
                <span class="icon">
                    @fas('tasks')
                </span>
                    {{ __('Background tasks') }}
                </a>
            </li>
        </ul>

        <ul class="menu-list">
            <li>
                <a class="has-text-info has-margin-top-lg" href="{{ url('/')}}">
                <span class="icon">
                    @fas('chevron-circle-left')
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
            @fas('chevron-circle-left')
        </span>
        {{ __('Collapse') }}
    </a>
</div>

@section('js')
    <script src="{{ mix('js/main/admin.js') }}"></script>
@stop
