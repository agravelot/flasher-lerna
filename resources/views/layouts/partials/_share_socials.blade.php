@foreach(config('socials-networks') as $name => $social)
    <a class="button is-black" href="{{ $social['url'] . urlencode(request()->url()) }}"
       target="_blank" rel="noreferrer" aria-label="{{ __('Share on') }} {{ $name }}">
        <span class="icon has-text-white">
            @fa($social['icon'])
        </span>
    </a>
@endforeach

<button class="button is-black is-share-button" aria-label="{{ __('Share') }}"
        data-title="{{ $title }}"
        data-text="{{ $text }}"
        data-url="{{ $url }}">
    <span>{{ __('Share') }}</span>
    <span class="icon has-text-white">
        @fa('share')
    </span>
</button>
