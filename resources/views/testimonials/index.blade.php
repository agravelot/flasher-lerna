@extends('layouts.app')

@section('pageTitle', __('Golden book'))

@section('content')
    <div class="container">
        @include('layouts.partials._messages')
{{--        <h1 class="title">{{ __('Golden book') }}</h1>--}}
        <a href="{{ route('testimonials.create') }}" class="button is-primary is-outlined is-medium has-margin-md">
            {{ __('Write') }}
        </a>

        @each('testimonials._goldenbook_item', $goldenBooksPosts, 'goldenBookPost', 'layouts.partials._empty')

        {{ $goldenBooksPosts->links() }}
    </div>
@endsection
