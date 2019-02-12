<div class="card has-margin-md">
    <div class="card-content">
        <p class="content">
            {{ $goldenBookPost->body }}
        </p>
        <p class="is-small">- {{ $goldenBookPost->name }}
            at {{ $goldenBookPost->created_at->toFormattedDateString() }}</p>
    </div>
</div>
