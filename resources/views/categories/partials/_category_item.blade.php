<div class="column is-half-tablet is-one-third-widescreen">
    <a href="{{ route('categories.show', ['category' => $category]) }}" aria-label="{{ $category->name }}">
        <div class="card has-hover-zoom">
            @if ($category->cover)
                <div class="card-image">
                    <figure class="image">
                        @php
                            /** @var \App\Models\Category $category */
                            $media = $category->cover
                        @endphp
                        {{ $media(\App\Models\Category::RESPONSIVE_CONVERSION,
                            [
                                'class' => 'responsive-media-lazy'
                            ]) }}
                    </figure>
                </div>
            @endif

            <div class="card-content">
                <h5 class="subtitle is-5">{{ $category->name }}</h5>
                @if ($category->meta_description)
                    <div class="content">
                        {{ $category->meta_description }}
                    </div>
                @endif
            </div>
        </div>
    </a>
</div>
