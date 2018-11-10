@extends('admin.admin')

@section('admin-content')
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                Albums
                <a href="{{ route('admin.albums.create') }}">
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
                <table class="table is-fullwidth is-striped is-hoverable">
                    <tbody>
                    @each('admin.albums._album_item', $albums, 'album', 'layouts.partials._empty')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{ $albums->links() }}
@endsection
