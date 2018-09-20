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
                        <label class="label">Password</label>
                        <div class="control">
                            <input class="input" id="password" type="password" name="password">
                        </div>
                        @include('layouts.partials._form_errors', ['data' => 'password'])
                    </div>


                    <div class="field">
                        <label class="label">Body</label>
                        <div class="control">
                            <textarea class="textarea" id="body" type="text" name="body"
                                      rows="10">{{ old('body', isset($album->body) ? $album->body : null) }}</textarea>
                        </div>
                        @include('layouts.partials._form_errors', ['data' => 'body'])
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
                        </div>
                    </div>
                </div>
            </div>


            <div class="card has-margin-bottom-md">
                <div class="card-content">
                    <div class="field is-horizontal">
                        <div class="field-label">
                            <label class="label">Cosplayers</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input class="input" type="tags" placeholder="Add Cosplayers"
                                           value="Tag1,Tag2,Tag3">
                                </div>
                            </div>
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
                            <input type="radio" name="foobar" checked>
                            Publish
                        </label>
                        <label class="radio">
                            <input type="radio" name="foobar">
                            Draft
                        </label>
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
                                <input type="checkbox" name="active"
                                       @if(old('active', isset($album->active) ? $album->active : false)) checked @endif>
                            </p>

                            @include('layouts.partials._form_errors', ['data' => 'active'])
                        </div>
                    </div>
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
                    azeaze
                </div>
            </div>
        </div>
    </div>
</form>
