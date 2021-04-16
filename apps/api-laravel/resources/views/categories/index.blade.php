@extends('layouts.app')

@section('pageTitle', __('Categories'))

@section('content')
    @if ($categories->isNotEmpty())
        <div class="hero is-black is-radiusless">
            <div class="hero-body"></div>
        </div>
    @endif

    <section class="section" style="margin-top: {{ $categories->isEmpty() ? '0' : '-120' }}px;">
        <div class="container">
            <div class="columns is-multiline">
                @each('categories.partials._category_item', $categories, 'category', 'layouts.partials._empty')
            </div>
            {{ $categories->links() }}
        </div>
    </section>
@endsection
