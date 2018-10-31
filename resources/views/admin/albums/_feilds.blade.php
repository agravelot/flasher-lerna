@section('head')
    <!-- TinyMCE -->
    <script src="{{ mix('js/tinymce.js') }}"></script>
@stop

<form method="POST" enctype="multipart/form-data" action="{{ $route }}">
    @csrf

    @if (isset($album))
        {{ method_field('PATCH') }}
    @endif

    <div class="columns">
        <div class="column is-two-thirds">
            <div class="card has-margin-bottom-md">
                <div class="card-content">

                    <div class="field">
                        <label class="label">Title</label>
                        <div class="control">
                            <input class="input" id="title" type="text" name="title"
                                   value="{{ old('title', isset($album->title) ? $album->title : null) }}"
                                   required autofocus>
                        </div>
                        @include('layouts.partials._form_errors', ['data' => 'title'])
                    </div>

                    <div class="field">
                        <label class="label">Body</label>
                        <div class="control">
                            <textarea class="textarea tinymce" id="body" type="text" name="body"
                                      rows="10">{{ old('body', isset($album->body) ? $album->body : null) }}</textarea>
                        </div>
                        @include('layouts.partials._form_errors', ['data' => 'body'])
                    </div>

                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control">
                            <input class="input" id="password" type="password" name="password">
                        </div>
                        @include('layouts.partials._form_errors', ['data' => 'password'])
                    </div>
                </div>
            </div>

            <div class="card has-margin-bottom-md">
                <div class="card-content">
                    <div class="field is-horizontal">
                        <div class="field-label">
                            <label class="label">Pictures</label>
                        </div>

                        <div class="field-body">
                            <div class="field">
                                <div class="file">
                                    <label class="file-label">
                                        <input class="file-input" type="file" name="pictures[]" multiple>
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

                                @include('layouts.partials._form_errors', ['data' => 'pictures'])
                            </div>

                            @if (isset($album) && $album->getMedia('thumb'))
                                @foreach($album->getMedia('thumb') as $picture)
                                    {{ $picture }}
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-one-third">
            <div class="card has-margin-bottom-md">
                <div class="card-header">
                    <div class="card-header-title">
                        Publish
                    </div>
                </div>
                <div class="card-content">
                    <div class="control">
                        <label class="radio">
                            <input type="radio" name="publish"
                                   value="1" {{ old('publish', isset($album->publish) && $album->publish) ? 'checked="checked"' : null  }}>
                            Publish
                        </label>
                        <label class="radio">
                            <input type="radio" name="publish"
                                   value="0" {{ old('publish', isset($album->publish) && $album->publish) ? null : 'checked="checked"' }}>
                            Draft
                        </label>
                    </div>
                    @include('layouts.partials._form_errors', ['data' => 'publish'])
                </div>

                <footer class="card-footer">
                    <div class="control card-footer-item">
                        <button class="button is-primary">
                            Send
                        </button>
                    </div>
                </footer>
            </div>

            <div class="card has-margin-bottom-md">
                <div class="card-header">
                    <div class="card-header-title">
                        Categories
                    </div>
                </div>
                <div class="card-content">
                    <div class="select is-multiple">
                        <select name="categories[]" id="categories" size="8" multiple="multiple">
                            @foreach($categories as $category)
                                @php($selected = '')
                                @if (isset($album) && $album->categories && $album->categories->contains($category->id) || in_array($category->id ,old('categories', [])))
                                    @php($selected = 'selected="selected"')
                                @endif
                                <option value="{{ $category->id }}" {{ $selected }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @include('layouts.partials._form_errors', ['data' => 'categories'])
                </div>
            </div>

            <div class="card has-margin-bottom-md">
                <div class="card-header">
                    <div class="card-header-title">
                        Cosplayers
                    </div>
                </div>
                <div class="card-content">
                    <div class="field is-horizontal">
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <div class="select is-multiple">
                                        <select name="cosplayers[]" id="cosplayers" size="8" multiple="multiple">
                                            @foreach($cosplayers as $cosplayer)
                                                @php($selected = '')
                                                @if (isset($album) && $album->cosplayers && $album->cosplayers->contains($cosplayer->id) || in_array($cosplayer->id ,old('cosplayers', [])))
                                                    @php($selected = 'selected="selected"')
                                                @endif
                                                <option value="{{ $cosplayer->id }}" {{ $selected }}>{{ $cosplayer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @include('layouts.partials._form_errors', ['data' => 'cosplayers'])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</form>
