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
        @include('layouts.partials._form_errors', ['data' => 'name'])
    </div>


    <div class="field">
        <label class="label">Body</label>
        <div class="control">
                <textarea class="textarea" id="description" type="text" name="description"
                          rows="10">{{ old('description', isset($cosplayer->description) ? $cosplayer->description : null) }}</textarea>
        </div>
        @include('layouts.partials._form_errors', ['data' => 'description'])
    </div>

    <div class="field">
        <div class="control">
            <button class="button is-primary">
                Send
            </button>
        </div>
    </div>
</form>

@section('js')
    <script src="/vendor/unisharp/laravel-ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace( 'description' );
    </script>
@stop