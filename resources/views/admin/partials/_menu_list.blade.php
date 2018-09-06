<div class="column is-3">
    <aside class="menu">
        <p class="menu-label">
            General
        </p>
        <ul class="menu-list">
            <li><a href="{{ route('dashboard') }}" {{ Request::is('admin/dashboard*') ? 'class=is-active' : '' }}>Dashboard</a></li>
            <li><a href="{{ route('admin_albums') }}" {{ Request::is('admin/albums*') ? 'class=is-active' : '' }}>Albums</a></li>
            <li><a href="{{ route('admin_contacts') }}" {{ Request::is('admin/contacts*') ? 'class=is-active' : '' }}>Contacts</a></li>
        </ul>
    </aside>
</div>