<div class="column is-two-thirds-tablet is-half-desktop is-one-third-widescreen is-one-quarter-fullhd">
    <a href="{{ route('goldenbook.show', ['goldenBookPost' => $goldenBookPost]) }}">
        <div class="card large">
            <div class="card-content">
                <div class="media">
                    <div class="media-content">
                        <p class="subtitle is-5">{{ $goldenBookPost->name }}</p>
                    </div>
                </div>
                <div class="content">
                    {{ $goldenBookPost->content }}
                </div>
            </div>
        </div>
    </a>
</div>
