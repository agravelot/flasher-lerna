<article class="media">
    <div class="media-content">
        <div class="content">

            <p class="is-italic has-text-centered is-family-secondary">
                <span class="icon is-inline-flex">
                    @fas('quote-left')
                </span>
                {{ $testimonial->body }}
                <span class="icon is-inline-flex">
                    @fas('quote-right')
                </span>
            </p>

            <p class="has-text-right has-text-weight-light">- {{ $testimonial->name }}</p>
            <p class="has-text-right has-text-weight-light">{{ $testimonial->created_at->toFormattedDateString() }}</p>
        </div>
    </div>
</article>
