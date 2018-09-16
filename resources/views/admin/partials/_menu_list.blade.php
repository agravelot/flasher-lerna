<div class="column is-2 is-sidebar-menu is-hidden-mobile">
    <aside class="menu">
        <p class="menu-label">
            Administration
        </p>
        <ul class="menu-list">
            <li><a href="{{ route('dashboard') }}" {{ Request::is('admin/dashboard*') ? 'class=is-active' : '' }}>Dashboard</a></li>
            <li><a href="{{ route('admin.albums.index') }}" {{ Request::is('admin/albums*') ? 'class=is-active' : '' }}>Albums</a>
            </li>
            <li>
                <a href="{{ route('admin.contacts.index') }}" {{ Request::is('admin/contacts*') ? 'class=is-active' : '' }}>Contacts</a>
            </li>
            <li>
                <a href="{{ route('admin.cosplayers.index') }}" {{ Request::is('admin/cosplayers*') ? 'class=is-active' : '' }}>Cosplayers</a>
            </li>
            <li>
                <a href="{{ route('admin.users.index') }}" {{ Request::is('admin/users*') ? 'class=is-active' : '' }}>Users</a>
            </li>

            {{--<li><a>Team Settings</a></li>--}}
            {{--<li>--}}
                {{--<a>Manage Your Team</a>--}}
                {{--<ul>--}}
                    {{--<li><a>Members</a></li>--}}
                    {{--<li><a>Plugins</a></li>--}}
                    {{--<li><a>Add a member</a></li>--}}
                {{--</ul>--}}
            {{--</li>--}}
            {{--<li><a>Invitations</a></li>--}}
            {{--<li><a>Cloud Storage Environment Settings</a></li>--}}
            {{--<li><a>Authentication</a></li>--}}
        {{--</ul>--}}
        {{--<p class="menu-label">--}}
            {{--Transactions--}}
        {{--</p>--}}
        {{--<ul class="menu-list">--}}
            {{--<li><a>Payments</a></li>--}}
            {{--<li><a>Transfers</a></li>--}}
            {{--<li><a>Balance</a></li>--}}
        {{--</ul>--}}
    </aside>

    <footer>

        <a href="{{ route('home') }}">Retour sur le site</a>
    </footer>
</div>