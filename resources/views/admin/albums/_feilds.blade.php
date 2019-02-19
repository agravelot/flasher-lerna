@section('head')
    <!-- TinyMCE -->
    <script src="{{ mix('js/tinymce.js') }}"></script>
@stop

<form method="POST" enctype="multipart/form-data" action="{{ $route }}">
    @csrf

    @if(isset($album))
        {{ method_field('PATCH') }}
    @endif

    <div class="columns">
        <div class="column is-two-thirds">
            <div class="card has-margin-bottom-md">
                <div class="card-content">

                    <div class="field">
                        <label class="label">{{ __('Title') }}</label>
                        <div class="control">
                            <input class="input" id="title" type="text" name="title"
                                   value="{{ old('title', $album->title ?? null) }}"
                                   required autofocus>
                        </div>
                        @include('layouts.partials._form_errors', ['data' => 'title'])
                    </div>

                    <div class="field">
                        <label class="label">{{ __('Body') }}</label>
                        <div class="control">
                            <textarea class="textarea tinymce" id="body" type="text" name="body"
                                      rows="10">{{ old('body', $album->body ?? null) }}</textarea>
                        </div>
                        @include('layouts.partials._form_errors', ['data' => 'body'])
                    </div>
                </div>
            </div>

            <div class="card has-margin-bottom-md">
                <div class="card-content">
                    <div class="field">
                        <label class="label">{{ __('Pictures') }}</label>

                        <div class="control">
                            <div class="file">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="pictures[]" multiple>
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
                        @include('layouts.partials._form_errors', ['data' => 'pictures'])
                    </div>
                </div>
            </div>
        </div>

        <div class="column is-one-third">
            <div class="card has-margin-bottom-md">
                <div class="card-header">
                    <div class="card-header-title">
                        {{ __('Publish') }}
                    </div>
                </div>
                <div class="card-content">
                    <div class="control">
                        <label class="radio">
                            <input type="radio" name="published_at"
                                   value="{{ $album->published_at ?? $currentDate}}"
                                    {{ old('published_at', isset($album->published_at) && $album->isPublished())
                                    ? 'checked="checked"' : null  }}
                            >
                            {{ __('Publish') }}
                        </label>
                        <label class="radio">
                            <input type="radio" name="published_at"
                                   value=""
                                    {{ old('published_at', isset($album->published_at) && $album->isPublished())
                                    ? null : 'checked="checked"' }}
                            >
                            {{ __('Draft') }}
                        </label>
                    </div>
                    @include('layouts.partials._form_errors', ['data' => 'publish'])

                    <div class="field">
                        <label class="label">{{ __('Private') }}</label>
                        <div class="control">
                            <input type="hidden" name="private" value="0">
                            <input class="checkbox" id="private" type="checkbox" name="private" value="1"
                                    {{ old('private', isset($album) && $album->private) ? 'checked="checked"' : '' }}
                            >
                        </div>
                        @include('layouts.partials._form_errors', ['data' => 'private'])
                    </div>
                </div>

                <footer class="card-footer">
                    <div class="control card-footer-item">
                        <button class="button is-primary">
                            {{ __('Send') }}
                        </button>
                    </div>
                </footer>
            </div>

            <div class="card has-margin-bottom-md">
                <div class="card-header">
                    <div class="card-header-title">
                        {{ __('Categories') }}
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
                        {{ __('Cosplayers') }}
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

@if (isset($album))
    <form method="post" action="{{ route('api.admin.album-pictures.store') }}" enctype="multipart/form-data"
          class="dropzone has-margin-bottom-md">
        @csrf
        <input type="hidden" name="album_slug" value="{{ $album->slug }}">
        <input type="file" name="file" style="display: none;">
    </form>

    <div class="card">
        <div class="card-content">
            <div class="field">
                <label class="label">{{ __('Pictures') }}</label>

                @if ($album->getMedia('thumb'))
                    @foreach($album->getMedia('pictures') as $key => $picture)
                        {{ $picture('thumb') }}
                        <a class="button has-text-danger"
                           onclick="event.preventDefault();document.getElementById('delete-picture-{{ $key }}').submit();">
                            {{ __('Delete') }}
                        </a>
                        <form id="delete-picture-{{ $key }}" method="POST" style="display: none;"
                              action="{{ route('api.admin.album-pictures.destroy', compact('album')) }}">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="album_slug" value="{{ $album->slug }}">
                            <input type="hidden" name="media_id" value="{{ $key }}">
                            <input type="submit" value="" style="display: none;">
                        </form>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endif
