@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card-content">
            <form method="POST" action="{{ route('albums.index') . '/' .  $album->id }}">
                {{ method_field('PATCH') }}
                @csrf

                <div class="field is-horizontal">
                    <div class="field-label">
                        <label class="label">Title</label>
                    </div>

                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <input class="input" id="title" type="text" name="title" value="{{ $album->title }}"
                                       required autofocus>
                            </p>

                            @if ($errors->has('title'))
                                <p class="help is-danger">
                                    {{ $errors->first('title') }}
                                </p>
                            @endif
                        </div>
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
                                <input type="checkbox" name="active" @if($album->active) checked @endif>
                            </p>

                            @if ($errors->has('active'))
                                <p class="help is-danger">
                                    {{ $errors->first('active') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label">
                        <label class="label">Password</label>
                    </div>

                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                {{--TODO Case reset password ?--}}
                                <input class="input" id="password" type="password" name="password">
                            </p>

                            @if ($errors->has('password'))
                                <p class="help is-danger">
                                    {{ $errors->first('password') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label">
                        <label class="label">Body</label>
                    </div>

                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <textarea class="textarea" id="body" type="text" name="body"
                                          rows="10">{{ $album->body }}</textarea>
                            </p>

                            @if ($errors->has('body'))
                                <p class="help is-danger">
                                    {{ $errors->first('body') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label">
                        <label class="label">Pictures</label>
                    </div>

                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                {{--TODO Afficher les actuels--}}
                                <input type="file" name="pictures[]" multiple/>
                            </p>

                            @if ($errors->has('pictures'))
                                <p class="help is-danger">
                                    {{ $errors->first('pictures') }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="field is-horizontal">
                    <div class="field-label">
                        <!-- Left empty for spacing -->
                    </div>
                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <button class="button is-primary">
                                    Send
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection