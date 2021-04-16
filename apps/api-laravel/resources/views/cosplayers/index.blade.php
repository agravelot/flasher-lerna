@extends('layouts.app')

@section('pageTitle', __('Cosplayers'))

@section('content')
    <section class="section">
        @include('layouts.partials._messages')
        <div class="container">
            <div class="columns is-multiline">
                @each('cosplayers.partials._cosplayer_item', $cosplayers, 'cosplayer', 'layouts.partials._empty')
            </div>
            {{ $cosplayers->links() }}
        </div>
    </section>
@endsection
