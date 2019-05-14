<footer class="footer">
    <div class="content has-text-centered">
        {!! settings()->get('footer_content') !!}
        <div class="has-text-centered">
            <i class="fas fa-code" style="color: dodgerblue;"></i>
            {{ __('with') }}
            <i class="fas fa-heart" style="color:red;"></i>
            {{ __('by') }} <a href="https://gitlab.com/Nevax">Antoine Gravelot</a>
        </div>
    </div>
</footer>
