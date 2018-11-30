<div class="card-content">
    <form class="register-form" method="POST" action="{{ $route }}">
        @csrf

        @if (isset($$category))
            {{ method_field('PATCH') }}
        @endif

        <div class="field">
            <label class="label">Name</label>
            <div class="control">
                <input class="input" id="name" type="text" name="name"
                       value="{{ old('name', isset($category->name) ? $category->name : null) }}" placeholder="Name"
                       required autofocus>
            </div>
            @include('layouts.partials._form_errors', ['data' => 'name'])
        </div>


        <div class="field">
            <label class="label">Description</label>
            <div class="control">
                <textarea class="textarea" id="description" type="text" name="description"
                          placeholder="Enter your description here"
                          rows="10">{{ old('description', isset($album->description) ? $album->description : null) }}</textarea>
            </div>
            @include('layouts.partials._form_errors', ['data' => 'body'])
        </div>

        <div class="field">
            <div class="control">
                <button type="submit" class="button is-primary">Save</button>
            </div>
        </div>
    </form>
</div>