@extends('admin.admin')

@section('admin-content')
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Users') }}
                <a href="{{ route('admin.users.create') }}">
                    <span class="icon">
                        @fas('plus')
                    </span>
                    <span>{{ __('Add') }}</span>
                </a>
            </p>
            <a href="#" class="card-header-icon" aria-label="more options">
                <span class="icon">
                    @fas('angle-down')
                </span>
            </a>
        </header>
        <div class="card-table">
            <div class="content">
                <table class="table is-hoverable is-striped">
                    <tbody>
                    @each('admin.users._user_item', $users, 'user', 'layouts.partials._empty')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @foreach($users as $user)
        @include('admin.partials._modal_confirm_delete', ['key' => $user->id, 'route' => route('admin.users.destroy', compact('user'))])
    @endforeach
    {{ $users->links() }}
@endsection
