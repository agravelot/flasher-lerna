@extends('layouts.app')

@section('pageTitle', __('Categories'))

@section('content')
    <section class="section">
        <div class="container">
            <div class="columns is-multiline">
                @each('categories.partials._category_item', $categories, 'category', 'layouts.partials._empty')
            </div>
            {{ $categories->links() }}
        </div>
    </section>
@endsection
