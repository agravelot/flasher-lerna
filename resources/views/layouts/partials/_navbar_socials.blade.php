@if (env('FACEBOOK_URL'))
    <a class="navbar-item {{ $desktop ? 'is-hidden-touch' : 'is-hidden-desktop' }}" href="{{ env('FACEBOOK_URL') }}"
       target="_blank">
        <span class="icon" style="color: #3b5998;">
            <i class="fab fa-facebook-f" aria-hidden="true"></i>
        </span>
    </a>
@endif

@if (env('INSTAGRAM_URL'))
    <a class="navbar-item {{ $desktop ? 'is-hidden-touch' : 'is-hidden-desktop' }}" href="{{ env('INSTAGRAM_URL') }}"
       target="_blank">
        <span class="icon" style="color: #f71a5d;">
            <i class="fab fa-instagram" aria-hidden="true"></i>
        </span>
    </a>
@endif