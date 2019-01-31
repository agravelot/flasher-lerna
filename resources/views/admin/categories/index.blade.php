@extends('admin.admin')

@section('admin-content')
    <div class="columns">
        <div class="column">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">
                        {{ __('Categories') }}
                        <a href="{{ route('admin.categories.create') }}">
                            <span class="icon">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span>{{ __('Add') }}</span>
                        </a>
                    </p>

                    <a href="#" class="card-header-icon" aria-label="more options">
                        <span class="icon">
                            <i class="fas fa-angle-down" aria-hidden="true"></i>
                        </span>
                    </a>
                </header>
                <div class="card-table">
                    <div class="content">
                        <table class="table is-fullwidth is-striped">
                            <tbody>
                            @each('admin.categories._category_item', $categories, 'category', 'layouts.partials._empty')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{ $categories->links() }}
        </div>
    </div>
@endsection
