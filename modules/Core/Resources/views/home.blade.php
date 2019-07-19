@extends('layouts.app')

@section('pageTitle', 'Photographe')

@section('content')
    <section class="hero is-black is-medium has-hero-background is-radiusless">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-vcentered">
                    <div class="column">
                        <figure class="image is-128x128 is-pulled-right">
                            <img class="is-rounded"
                                 src="{{ optional(settings()->get('profile_picture_homepage'))->getUrl() }}"
                                 srcset="{{ optional(settings()->get('profile_picture_homepage'))->getSrcset() }}"
                                 alt="{{ optional(settings()->get('profile_picture_homepage'))->name }}">
                        </figure>
                    </div>
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
                    <h2 class="title">{{ __('Discover my albums') }}</h2>
                </a>
            </div>

            <section>
                <div class="columns is-vcentered is-multiline is-mobile">
                    @foreach($albums as $album)
                        <div class="column is-half-touch">
                            <a class="box is-paddingless has-hover-zoom is-clipped"
                               href="{{ route('albums.show', compact('album')) }}">
                                <figure class="image is-square is-fitted">
                                    {{ $album->coverResponsive }}
                                </figure>
                            </a>
                        </div>
                    @endforeach
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
                    <a href="{{ route('goldenbook.index') }}">
                        <h2 class="title">{{ __('Testimonials') }}</h2>
                    </a>
                </div>

                <div class="columns is-vcentered">
                    @foreach($testimonials as $testimonial)
                        <div class="column">
                            <div class="box">
                                <article class="media">
                                    <div class="media-content">
                                        <div class="content">

                                            <p class="is-italic has-text-centered is-family-secondary">
                                                 <span class="icon is-inline-flex">
                                                  @fas('quote-left')
                                                </span>
                                                {{ $testimonial->body }}
                                                <span class="icon is-inline-flex">
                                                  @fas('quote-right')
                                                </span>
                                            </p>

                                            <p class="has-text-right has-text-weight-light">- {{ $testimonial->name }}</p>
                                        </div>
                                    </div>
                                </article>
                            </div>
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
