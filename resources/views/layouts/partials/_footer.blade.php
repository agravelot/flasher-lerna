<footer class="footer">
    <div class="content has-text-centered">
        <div class="columns is-centered">
            <div class="column is-narrow">
                <div class="has-text-centered">
                    <a href="{{ url('/') }}">{{ __('Home') }}</a>
                </div>
            </div>
            <div class="column is-narrow">
                <div class="has-text-centered">
                    <a href="{{ route('albums.index')  }}">{{ __('Albums') }}</a>
                </div>
            </div>
            <div class="column is-narrow">
                <div class="has-text-centered">
                    <a href="{{ route('categories.index')  }}">{{ __('Categories') }}</a>
                </div>
            </div>
            <div class="column is-narrow">
                <div class="has-text-centered">
                    <a href="{{ route('goldenbook.index')  }}">{{ __('Golden book') }}</a>
                </div>
            </div>
            <div class="column is-narrow">
                <div class="has-text-centered">
                    <a href="{{ route('contact.index')  }}">{{ __('Contact') }}</a>
                </div>
            </div>
        </div>


        {!! settings()->get('footer_content') !!}
        <div class="has-text-centered">
            <i class="fas fa-code" style="color: dodgerblue;"></i>
            {{ __('with') }}
            <i class="fas fa-heart" style="color:red;"></i>
            {{ __('by') }} <a href="https://gitlab.com/Nevax">Antoine Gravelot</a>
        </div>
    </div>
</footer>
