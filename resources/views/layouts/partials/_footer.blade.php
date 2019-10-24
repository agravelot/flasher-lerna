<section class="hero is-black is-radiusless">
    <div class="hero-body">
        <div class="content has-text-centered">
            <div class="columns is-centered">
                <div class="column is-narrow">
                    <div class="has-text-centered">
                        <a class="has-text-white" href="{{ url('/') }}">{{ __('Home') }}</a>
                    </div>
                </div>
                <div class="column is-narrow">
                    <div class="has-text-centered">
                        <a class="has-text-white" href="{{ route('albums.index')  }}">{{ __('Albums') }}</a>
                    </div>
                </div>
                <div class="column is-narrow">
                    <div class="has-text-centered">
                        <a class="has-text-white" href="{{ route('categories.index')  }}">{{ __('Categories') }}</a>
                    </div>
                    <div class="column is-narrow">
                        <div class="has-text-centered">
                            <a class="has-text-white"
                               href="{{ route('testimonials.index')  }}">{{ __('Testimonial') }}</a>
                        </div>
                    </div>
                </div>
                <div class="column is-narrow">
                    <div class="has-text-centered">
                        <a class="has-text-white" href="{{ route('contact.index')  }}">{{ __('Contact') }}</a>
                    </div>
                </div>
            </div>

            @include('layouts.partials._navbar_socials', ['class' => 'button is-text is-black'])

                <p>{!! settings()->get('footer_content') !!}</p>
                <div class="has-text-centered">
                <span class="icon" style="color: dodgerblue;">
                    @fas('code')
                </span>
                    {{ __('with') }}
                    <span class="icon" style="color: red;">
                @fas('heart')
            </span>
                    {{ __('by') }} <a class="has-text-white" href="https://gitlab.com/Nevax">Antoine Gravelot</a>
                </div>
            </div>
        </div>
    </div>
</section>
