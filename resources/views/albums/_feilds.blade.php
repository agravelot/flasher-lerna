<form method="POST" enctype="multipart/form-data" action="{{ $route }}">
    @csrf

    @if (isset($album))
        {{ method_field('PATCH') }}
    @endif

    <div class="field is-horizontal">
        <div class="field-label">
            <label class="label">Title</label>
        </div>

        <div class="field-body">
            <div class="field">
                <p class="control">
                    <input class="input" id="title" type="text" name="title" value="{{ old('title', isset($album->title) ? $album->title : null) }}"
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
            <label class="label">Active</label>
        </div>

        <div class="field-body">
            <div class="field">
                <p class="control">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" @if(old('active', isset($album->active) ? $album->active : false)) checked @endif>
                </p>

                @if ($errors->has('active'))
                    <p class="help is-danger">
                        {{ $errors->first('active') }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label">
            <label class="label">Password</label>
        </div>

        <div class="field-body">
            <div class="field">
                <p class="control">
                    <input class="input" id="password" type="password" name="password">
                </p>

                @if ($errors->has('password'))
                    <p class="help is-danger">
                        {{ $errors->first('password') }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label">
            <label class="label">Body</label>
        </div>

        <div class="field-body">
            <div class="field">
                <p class="control">
                    <textarea class="textarea" id="body" type="text" name="body" rows="10">{{ old('body', isset($album->body) ? $album->body : null) }}</textarea>
                </p>

                @if ($errors->has('body'))
                    <p class="help is-danger">
                        {{ $errors->first('body') }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label">
            <label class="label">Pictures</label>
        </div>

        <div class="field-body">
            <div class="field">
                <p class="control">
                    <input type="file" name="pictures[]" multiple />
                </p>

                @if ($errors->has('pictures'))
                    <p class="help is-danger">
                        {{ $errors->first('pictures') }}
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