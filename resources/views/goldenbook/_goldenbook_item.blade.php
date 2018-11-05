<div class="card large has-margin-md">
    <div class="card-content">
        <div class="content">
            {{ $goldenBookPost->body }}
        </div>
        <p class="is-small">-{{ $goldenBookPost->name }} at {{ $goldenBookPost->created_at->toFormattedDateString() }}</p>
    </div>
</div>
