@foreach($socialMedias as $media)
    <a class="navbar-item" href="{{ $media->url }}"
       target="_blank" rel="noreferrer" aria-label="{{ $media->name }}">
        <span class="icon" style="color: {{ $media->color }};">
            @fa($media->icon)
        </span>
    </a>
@endforeach
