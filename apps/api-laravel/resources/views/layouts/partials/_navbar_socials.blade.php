@foreach($socialMedias as $media)
    <a class="{{ $class }}" href="{{ $media->url }}"
       target="_blank" rel="noreferrer" aria-label="{{ $media->name }}">
        <span class="icon" style="color: {{ $media->color }};">
            <x-fa :name="$media->icon"/>
        </span>
    </a>
@endforeach
