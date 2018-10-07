<div class="card-content">
    <form class="register-form" method="POST" action="{{ $route }}">

        @csrf

        @if (isset($$category))
            {{ method_field('PATCH') }}
        @endif

        <div class="field is-horizontal">
            <div class="field-label">
                <label class="label">Name</label>
            </div>

            <div class="field-body">
                <div class="field">
                    <p class="control">
                        <input class="input" id="name" type="name" name="name"
                               value="{{ old('name', isset($category->name) ? $category->name : null) }}"
                               required autofocus>
                    </p>
                    @include('layouts.partials._form_errors', ['data' => 'name'])
                </div>
            </div>
        </div>

        <div class="field">
            <label class="label">Description</label>
            <div class="control">
                <textarea class="textarea" id="body" type="text" name="body"
                          rows="10">{{ old('body', isset($album->body) ? $album->body : null) }}</textarea>
            </div>
            @include('layouts.partials._form_errors', ['data' => 'body'])
        </div>

        <div class="field is-horizontal">
            <div class="field-label"></div>

            <div class="field-body">
                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" class="button is-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>