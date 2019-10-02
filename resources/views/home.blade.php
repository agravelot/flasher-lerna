@extends('layouts.app')

@include('layouts.partials._og_tags', ['openGraph' => new App\Http\OpenGraphs\HomeOpenGraph()])

@section('content')
    <section class="hero is-black is-medium has-hero-background is-radiusless">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    @php
                        $profilePicture = settings()->get('profile_picture_homepage');
                    @endphp
                    @if ($profilePicture)
                        <div class="column">
                            <figure class="image is-128x128 is-pulled-right">
                                {{ $profilePicture('', ['class' => 'is-rounded responsive-media']) }}
                            </figure>
                        </div>
                    @endif
                    <div class="column">
                        <h1 class="title">
                            JKanda
                        </h1>
                        <p class="subtitle">
                            Photographe passionnée
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="has-text-centered has-margin-bottom-lg">
                <a href="{{ route('albums.index') }}">
                    <h2 class="title">{{ __('Discover my last albums') }}</h2>
                </a>
            </div>

            <section>
                <div class="columns is-vcentered is-multiline">
                    @foreach($albums as $key => $album)
                        <div class="column {{ $key === 0 ? 'is-full-touch' : null }}">
                            <a href="{{ route('albums.show', compact('album')) }}"
                               class="has-margin-right-md" aria-label="{{ $album->title }}">
                                <article class="card has-hover-zoom is-clipped">
                                    <div class="card-image">
                                        <figure class="image">
                                            {{ $album->coverResponsive }}
                                        </figure>
                                    </div>
                                    <div class="card-content">
                                        <div class="content">
                                            <h3 class="title">{{ $album->title }}</h3>
                                            <div class="tags">
                                                @foreach($album->categories as $category)
                                                    <span class="tag">
                                                {{ $category->name }}
                                            </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="has-text-centered">
                    <a class="button is-medium is-white has-hover-zoom" href="{{ route('albums.index') }}">
                        <span>{{ __('Discover more') }}</span>
                        <span class="icon is-medium">
                        @fa('chevron-right')
                    </span>
                    </a>
                </div>
            </section>

        </div>
    </section>

    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h2 class="title">Qui suis-je ?</h2>
                <p>
                    {!! settings()->get('homepage_description') !!}
                </p>
            </div>
            {{--            <section class="has-margin-top-lg">--}}
            {{--                <h3 class="title is-5 has-text-centered">Soyez informé dès que je publie quelque chose de nouveau.</h3>--}}
            {{--                <div class="field has-addons has-addons-centered has-margin-top-sm">--}}
            {{--                    <div class="control">--}}
            {{--                        <input class="input" type="text" placeholder="{{ __('Votre addresse email') }}">--}}
            {{--                    </div>--}}
            {{--                    <div class="control">--}}
            {{--                        <a class="button is-info">--}}
            {{--                            {{ __('Valider') }}--}}
            {{--                        </a>--}}
            {{--                    </div>--}}
            {{--                </div>--}}
            {{--            </section>--}}
        </div>
    </section>

    <section class="hero">
        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="has-text-centered has-margin-bottom-lg">
                    <a href="{{ route('testimonials.index') }}">
                        <h2 class="title">{{ __('Testimonials') }}</h2>
                    </a>
                </div>

                <div class="columns is-vcentered">
                    @foreach($testimonials as $testimonial)
                        <div class="column">
                            @include('testimonials._item', ['goldenBookPost' => $testimonial])
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>
@stop

@section('head')
    <style>
        .has-hero-background {
            background: url("{{ optional(settings()->get('background_picture_homepage'))->getUrl() }}") center center;
            background-size: cover;
        }
    </style>
@endsection
