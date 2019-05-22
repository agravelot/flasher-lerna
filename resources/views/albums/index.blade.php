@extends('layouts.app')

@section('pageTitle', __('My albums'))

@section('content')
    <section class="section">
        <div class="container">
            @include('albums.partials._index_item', compact('albums'))
        </div>
    </section>
@endsection
