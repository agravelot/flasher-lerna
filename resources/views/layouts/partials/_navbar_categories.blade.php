@if(!$categories->isEmpty())
    <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link" href="{{ route('categories.index') }}">
            {{ __('Categories') }}
        </a>
        <div class="navbar-dropdown is-boxed">
            @foreach($categories as $category)
                <a class="navbar-item" href="{{ route('categories.show', compact('category')) }}">
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
    </div>
@endif