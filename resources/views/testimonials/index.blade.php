@extends('layouts.app')

@section('pageTitle', __('Testimonial'))

@section('content')
    <div class="container">
        <div class="has-margin-top-md">
            @include('layouts.partials._messages')
        </div>
{{--        <h1 class="title">{{ __('Testimonials') }}</h1>--}}
        <a href="{{ route('testimonials.create') }}" class="button is-primary is-outlined is-medium has-margin-md">
            {{ __('Write') }}
        </a>

        @each('testimonials._item', $testimonials, 'testimonial', 'layouts.partials._empty')

        {{ $testimonials->links() }}
    </div>
@endsection
