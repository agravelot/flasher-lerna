<div class="card-content">
    <form class="register-form" method="POST" action="{{ $route }}">
        @csrf

        @if (isset($socialMedia))
            {{ method_field('PATCH') }}
        @endif

        <div class="field">
            <label class="label">{{ __('Name') }}</label>
            <div class="control">
                <input class="input" id="name" type="text" name="name"
                       value="{{ old('name', isset($socialMedia->name) ? $socialMedia->name : null) }}" placeholder="{{ __('Name') }}"
                       required autofocus>
            </div>
            @include('layouts.partials._form_errors', ['data' => 'name'])
        </div>

        <div class="field">
            <label class="label">{{ __('Icon') }}</label>
            <div class="control">
                <input class="input" id="icon" type="text" name="icon"
                       value="{{ old('icon', isset($socialMedia->icon) ? $socialMedia->icon : null) }}" placeholder="{{ __('Icon') }}"
                       required autofocus>
            </div>
            @include('layouts.partials._form_errors', ['data' => 'icon'])
        </div>

        <div class="field">
            <label class="label">{{ __('Color') }}</label>
            <div class="control">
                <input class="input" id="color" type="text" name="color"
                       value="{{ old('color', isset($socialMedia->color) ? $socialMedia->color : null) }}" placeholder="{{ __('Color') }}"
                       required autofocus>
            </div>
            @include('layouts.partials._form_errors', ['data' => 'color'])
        </div>

        <div class="field">
            <label class="label">{{ __('Link') }}</label>
            <div class="control">
                <input class="input" id="url" type="text" name="url"
                       value="{{ old('url', isset($socialMedia->url) ? $socialMedia->url : null) }}" placeholder="{{ __('Link') }}"
                       required autofocus>
            </div>
            @include('layouts.partials._form_errors', ['data' => 'url'])
        </div>

        <div class="field">
            <label class="label">{{ __('Active') }}</label>
            <div class="control">
                <input type="hidden" name="active" value="0">
                <input class="checkbox" id="active" type="checkbox" name="active" value="1"
                        {{ old('active', isset($socialMedia) && $socialMedia->active) ? 'checked="checked"' : '' }}
                >
            </div>
            @include('layouts.partials._form_errors', ['data' => 'active'])
        </div>

        <div class="field">
            <div class="control">
                <button type="submit" class="button is-primary">{{ __('Send') }}</button>
            </div>
        </div>
    </form>
</div>