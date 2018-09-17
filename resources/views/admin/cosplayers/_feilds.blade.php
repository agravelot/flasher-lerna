<form method="POST" enctype="multipart/form-data" action="{{ $route }}">
    @csrf

    @if (isset($cosplayer))
        {{ method_field('PATCH') }}
    @endif

    <div class="field">
        <label class="label">Name</label>
        <div class="control">
            <input class="input" id="name" type="text" name="name"
                   value="{{ old('name', isset($cosplayer->name) ? $cosplayer->name : null) }}"
                   required autofocus>
        </div>
        @if ($errors->has('name'))
            <p class="help is-danger">
                {{ $errors->first('name') }}
            </p>
        @endif
    </div>


    <div class="field">
        <label class="label">Body</label>
        <div class="control">
                <textarea class="textarea" id="description" type="text" name="description"
                          rows="10">{{ old('description', isset($cosplayer->description) ? $cosplayer->description : null) }}</textarea>
        </div>
        @if ($errors->has('description'))
            <p class="help is-danger">
                {{ $errors->first('description') }}
            </p>
        @endif
    </div>

    <div class="field">
        <div class="control">
            <button class="button is-primary">
                Send
            </button>
        </div>
    </div>
</form>