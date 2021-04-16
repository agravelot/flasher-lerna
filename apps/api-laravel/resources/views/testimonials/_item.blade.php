<div class="media">
    <div class="media-content">
        <div class="is-italic has-text-centered is-family-secondary">
            <span class="icon is-inline-flex">
                <x-fa name="quote-left" library="solid"/>
            </span>
            <blockquote>{{ $testimonial->body }}</blockquote>
            <span class="icon is-inline-flex">
                <x-fa name="quote-right" library="solid"/>
            </span>
        </div>

        <p class="has-text-right has-text-weight-light">- {{ $testimonial->name }}</p>
        <p class="has-text-right has-text-weight-light">{{ $testimonial->created_at->toFormattedDateString() }}</p>
    </div>
</div>
