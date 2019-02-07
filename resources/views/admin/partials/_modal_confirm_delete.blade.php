<div id="modal-delete-{{ $key }}" class="modal modal-fx-fadeInScale">
    <div class="modal-background"></div>
    <div class="modal-content">
        <div class="card">
            <section class="modal-card-body is-titleless">
                <div class="media">
                    <div class="media-content"><p>{{ __('Are you sure?') }}</p></div>
                </div>
            </section>
            <footer class="modal-card-foot">
                <button class="button modal-button-close">
                    {{ __('Cancel') }}
                </button>
                <form action="{{ $route }}" method="POST">
                    {{ method_field('DELETE') }}
                    @csrf
                    <button class="button is-danger">
                        {{ __('Yes') }}
                    </button>
                </form>
            </footer>
        </div>
    </div>
    <button class="modal-close is-large" aria-label="close"></button>
</div>