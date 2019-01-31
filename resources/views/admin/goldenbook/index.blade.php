@extends('admin.admin')

@section('admin-content')
    <div class="columns">
        <div class="column">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">
                        Golden book posts
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
                            @each('admin.goldenbook._goldenbook_item', $goldenBookPosts, 'goldenBookPost', 'layouts.partials._empty')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{ $goldenBookPosts->links() }}
        </div>
    </div>
@endsection
