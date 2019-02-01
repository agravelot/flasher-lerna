@if (env('FACEBOOK_URL'))
    <a class="navbar-item {{ $desktop ? 'is-hidden-touch' : 'is-hidden-desktop' }}" href="{{ env('FACEBOOK_URL') }}"
       target="_blank" rel="noreferrer">
        <span class="icon" style="color: #3b5998;">
            <i class="fab fa-facebook-f" aria-label="facebook"></i>
        </span>
    </a>
@endif

@if (env('INSTAGRAM_URL'))
    <a class="navbar-item {{ $desktop ? 'is-hidden-touch' : 'is-hidden-desktop' }}" href="{{ env('INSTAGRAM_URL') }}"
       target="_blank" rel="noreferrer">
        <span class="icon" style="color: #f71a5d;">
            <i class="fab fa-instagram" aria-label="instagram"></i>
        </span>
    </a>
@endif