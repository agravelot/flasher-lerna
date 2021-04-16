@extends('layouts.app')

@section('pageTitle', __('Testimonial'))

@section('content')
    <section class="section">
        <div class="content">
            <div class="container">
                <div class="has-margin-top-md">
                    @include('layouts.partials._messages')
                </div>
                <a href="{{ route('testimonials.create') }}" class="button is-primary is-outlined is-medium has-margin-md">
                    {{ __('Write') }}
                </a>

                <div class="columns is-multiline">
                    @forelse($testimonials as $testimonial)
                        <div class="column is-half-desktop is-full-touch">
                            @include('testimonials._item', compact('testimonial'))
                        </div>
                    @empty
                        @include('layouts.partials._empty')
                    @endforelse
                </div>

                {{ $testimonials->links() }}
            </div>
        </div>
    </section>
@endsection
