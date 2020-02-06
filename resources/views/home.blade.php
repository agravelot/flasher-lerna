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
                                <img src="{{ $profilePicture->getUrl('thumb') }}"
                                     alt="Avatar de {{ settings()->get('app_name') }}" class="is-rounded">
                            </figure>
                        </div>
                    @endif
                    <div class="column">
                        <h1 class="title">
                            {{ settings()->get('app_name') }}
                        </h1>
                        <p class="subtitle">
                            {{ settings()->get('homepage_header_subtitle') }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- HTML Markup -->
    <div class="aa-input-container" id="aa-input-container">
        <input type="search" id="aa-search-input" class="aa-input-search" placeholder="Rechercher un album, un cosplayer..." name="search" autocomplete="off" />
        <svg class="aa-input-icon" viewBox="654 -372 1664 1664">
            <path d="M1806,332c0-123.3-43.8-228.8-131.5-316.5C1586.8-72.2,1481.3-116,1358-116s-228.8,43.8-316.5,131.5  C953.8,103.2,910,208.7,910,332s43.8,228.8,131.5,316.5C1129.2,736.2,1234.7,780,1358,780s228.8-43.8,316.5-131.5  C1762.2,560.8,1806,455.3,1806,332z M2318,1164c0,34.7-12.7,64.7-38,90s-55.3,38-90,38c-36,0-66-12.7-90-38l-343-342  c-119.3,82.7-252.3,124-399,124c-95.3,0-186.5-18.5-273.5-55.5s-162-87-225-150s-113-138-150-225S654,427.3,654,332  s18.5-186.5,55.5-273.5s87-162,150-225s138-113,225-150S1262.7-372,1358-372s186.5,18.5,273.5,55.5s162,87,225,150s113,138,150,225  S2062,236.7,2062,332c0,146.7-41.3,279.7-124,399l343,343C2305.7,1098.7,2318,1128.7,2318,1164z" />
        </svg>
    </div>

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

    <section class="hero is-black">
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
                            @include('testimonials._item', ['testimonial' => $testimonial])
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>
@stop
