@section('head')
    <!-- TinyMCE -->
    <script src="{{ mix('js/tinymce.js') }}"></script>
@stop

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
                <textarea class="textarea tinymce" id="description" type="text" name="description"
                          rows="10">{{ old('description', isset($cosplayer->description) ? $cosplayer->description : null) }}</textarea>
        </div>
        @include('layouts.partials._form_errors', ['data' => 'description'])
    </div>

    <div class="card-content">
        <div class="field">
            <label class="label">Avatar</label>
            @if (isset($cosplayer) && $cosplayer->getFirstMediaUrl('avatar') )
                <img src="{{ $cosplayer->getFirstMediaUrl('avatar', 'thumb')  }}" alt="">
            @endif

            <div class="control">
                <div class="file">
                    <label class="file-label">
                        <input class="file-input" type="file" name="avatar">
                        <span class="file-cta">
                            <span class="file-icon">
                                <i class="fas fa-upload"></i>
                            </span>
                            <span class="file-label">
                                Choose your files…
                            </span>
                        </span>
                    </label>
                </div>
            </div>
            @include('layouts.partials._form_errors', ['data' => 'avatar'])
        </div>
    </div>

    <div class="field">
        <div class="control">
            <button class="button is-primary">
                Send
            </button>
        </div>
    </div>
</form>