@extends('admin.admin')

@section('admin-content')
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                Cosplayers
                <a href="{{ route('admin.cosplayers.create') }}">
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
                    @each('admin.cosplayers._cosplayer_item', $cosplayers, 'cosplayer', 'layouts.partials._empty')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $cosplayers->links() }}
@endsection
