@if(!$categories->isEmpty())
    <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="{{ route('categories.index') }}">
            {{ __('Categories') }}
        </a>
        <div class="navbar-dropdown is-boxed">
            <a class="navbar-item" href="{{ route('categories.index') }}">
                {{ __('Show all categories') }}
            </a>
            <hr class="navbar-divider">
            @foreach($categories as $category)
                <a class="navbar-item" href="{{ route('categories.show', compact('category')) }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>
@endif