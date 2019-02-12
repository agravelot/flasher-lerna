<div class="column is-half-tablet is-one-third-widescreen">
    <a href="{{ route('categories.show', ['categories' => $category]) }}">
        <div class="card">
            <div class="card-content">
                <h5 class="subtitle is-5">{{ $category->name }}</h5>
                <div class="content">
                    {{ $category->description }}
                </div>
            </div>
        </div>
    </a>
</div>
