@extends('layouts.app')

@section('pageTitle', __('Testimonial'))

@section('content')
    <div class="container">
        <div class="has-margin-top-md">
            @include('layouts.partials._messages')
        </div>
        <a href="{{ route('testimonials.create') }}" class="button is-primary is-outlined is-medium has-margin-md">
            {{ __('Write') }}
        </a>

        <div class="columns is-multiline">
            @each('testimonials._item', $testimonials, 'testimonial', 'layouts.partials._empty')
        </div>

        {{ $testimonials->links() }}
    </div>
@endsection
