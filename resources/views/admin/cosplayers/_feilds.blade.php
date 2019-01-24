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
        <label class="label">{{ __('Name') }}</label>
        <div class="control">
            <input class="input" id="name" type="text" name="name"
                   value="{{ old('name', isset($cosplayer->name) ? $cosplayer->name : null) }}"
                   required autofocus>
        </div>
        @include('layouts.partials._form_errors', ['data' => 'name'])
    </div>

    <div class="field">
        <label class="label">{{ __('Description') }}</label>
        <div class="control">
                <textarea class="textarea tinymce" id="description" type="text" name="description"
                          rows="10">{{ old('description', isset($cosplayer->description) ? $cosplayer->description : null) }}</textarea>
        </div>
        @include('layouts.partials._form_errors', ['data' => 'description'])
    </div>

    <div class="card-content">
        <div class="field">
            <label class="label">{{ __('Avatar') }}</label>
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
                                {{ __('Choose your files…') }}
                            </span>
                        </span>
                    </label>
                </div>
            </div>
            @include('layouts.partials._form_errors', ['data' => 'avatar'])
        </div>
    </div>

    <div class="field is-horizontal">
        <div class="field-label">
            <label class="label">{{ __('Related user') }}</label>
        </div>

        <div class="field-body">
            <div class="field">
                <div class="control has-icons-left">
                    <div class="select">
                        <select name="user_id">
                            <option value=""> None</option>
                            @foreach($users as $user )
                                @php
                                    $options = null;
                                    if (isset($cosplayer->user) && $cosplayer->user->id === $user->id) {
                                        $options = 'selected';
                                    } elseif (isset($user->cosplayer)) {
                                        $options = 'disabled';
                                    }
                                @endphp
                                <option value="{{ $user->id }}" {{ $options }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <span class="icon is-medium is-left">
                            <i class="fas fa-user-tag"></i>
                        </span>
                </div>
                @include('layouts.partials._form_errors', ['data' => 'cosplayer'])
            </div>
        </div>
    </div>

    <div class="field">
        <div class="control">
            <button class="button is-primary">
                {{ __('Send') }}
            </button>
        </div>
    </div>
</form>