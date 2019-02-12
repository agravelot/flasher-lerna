@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card-content">

            <h1 class="title is-1 has-text-centered">{{ __('Add your message') }}</h1>

            @include('layouts.partials._messages')

            <form method="POST" action="{{ route('goldenbook.store') }}">
                @csrf

                <div class="field is-horizontal">
                    <div class="field-label">
                        <label class="label">{{ __('Name') }}</label>
                    </div>

                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <input class="input" id="name" type="text" name="name" value="{{ old('name') }}"
                                       required autofocus>
                            </p>

                            @include('layouts.partials._form_errors', ['data' => 'name'])
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label">
                        <label class="label">{{ __('Email') }}</label>
                    </div>

                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <input class="input" id="email" type="text" name="email" value="{{ old('email') }}"
                                       required autofocus>
                            </p>

                            @include('layouts.partials._form_errors', ['data' => 'email'])
                        </div>
                    </div>
                </div>

                <div class="field is-horizontal">
                    <div class="field-label">
                        <label class="label">{{ __('Message') }}</label>
                    </div>

                    <div class="field-body">
                        <div class="field">
                            <div class="control">
                                <textarea class="textarea" id="body" type="text" name="body"
                                          rows="10">{{ old('body') }}</textarea>
                            </div>

                            @include('layouts.partials._form_errors', ['data' => 'body'])
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
                                {!! NoCaptcha::display() !!}
                            </div>
                            @include('layouts.partials._form_errors', ['data' => 'g-recaptcha-response'])
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
                                    {{ __('Send') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    {!! NoCaptcha::renderJs() !!}
@endsection
