@extends('admin.admin')

@section('admin-content')
    <div class="card">
        <header class="card-header">
            <p class="card-header-title">
                Cosplayers
                <a href="{{ route('admin.cosplayers.create') }}">
                    <span class="icon">
                        @fas('plus')
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
                <table class="table is-hoverable is-striped">
                    <tbody>
                    @each('admin.cosplayers._cosplayer_item', $cosplayers, 'cosplayer', 'layouts.partials._empty')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @foreach($cosplayers as $cosplayer)
        @include('admin.partials._modal_confirm_delete', ['key' => $cosplayer->id, 'route' => route('admin.cosplayers.destroy', compact('cosplayer'))])
    @endforeach
    {{ $cosplayers->links() }}
@endsection
