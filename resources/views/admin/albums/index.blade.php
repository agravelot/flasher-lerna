@extends('admin.admin')

@section('admin-content')
    <div id="app">
        <div class="container">
            <albums></albums>
        </div>
    </div>

    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                {{ __('Albums') }}
                <a href="{{ route('admin.albums.create') }}">
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
                <table class="table is-striped is-hoverable">
                    <tbody>
                    @each('admin.albums._album_item', $albums, 'album', 'layouts.partials._empty')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @foreach($albums as $album)
        @include('admin.partials._modal_confirm_delete', ['key' => $album->id, 'route' => route('admin.albums.destroy', compact('album'))])
    @endforeach
    {{ $albums->links() }}
@endsection
