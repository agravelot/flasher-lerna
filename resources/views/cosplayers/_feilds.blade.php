<form method="POST" enctype="multipart/form-data" action="{{ $route }}">
    @csrf

    @if (isset($cosplayer))
        {{ method_field('PATCH') }}
    @endif

    <div class="field is-horizontal">
        <div class="field-label">
            <label class="label">Title</label>
        </div>

        <div class="field-body">
            <div class="field">
                <p class="control">
                    <input class="input" id="name" type="text" name="name"
                           value="{{ old('name', isset($cosplayer->name) ? $cosplayer->name : null) }}"
                           required autofocus>
                </p>

                @if ($errors->has('title'))
                    <p class="help is-danger">
                        {{ $errors->first('title') }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label">
            <!-- Left empty for spacing -->
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <button class="button is-primary">
                        Send
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>