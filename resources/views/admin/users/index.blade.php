@extends('admin.admin')

@section('admin-content')
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                Users
                <a href="{{ route('admin.users.create') }}">
                            <span class="icon">
                                <i class="fas fa-plus"></i>
                            </span>
                    <span>Add</span>
                </a>
            </p>
            <a href="#" class="card-header-icon" aria-label="more options">
                        <span class="icon">
                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                        </span>
            </a>
        </header>
        <div class="card-table">
            <div class="content">
                <table class="table is-fullwidth is-striped">
                    <tbody>
                    @each('admin.users._user_item', $users, 'user', 'layouts.partials._empty')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $users->links() }}
@endsection
