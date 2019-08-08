<article class="media">
    <div class="media-content">
        <div class="content">

            <p class="is-italic has-text-centered is-family-secondary">
                <span class="icon is-inline-flex">
                    @fas('quote-left')
                </span>
                {{ $goldenBookPost->body }}
                <span class="icon is-inline-flex">
                    @fas('quote-right')
                </span>
            </p>

            <p class="has-text-right has-text-weight-light">- {{ $goldenBookPost->name }}</p>
            <p class="has-text-right has-text-weight-light">{{ $goldenBookPost->created_at->toFormattedDateString() }}</p>
        </div>
    </div>
</article>
