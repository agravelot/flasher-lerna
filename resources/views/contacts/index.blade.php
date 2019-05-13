@extends('layouts.app')

@section('pageTitle', __('Contact me'))

@section('content')
    <section class="section">
        <div class="container">
            <div class="card-content">
                @include('layouts.partials._messages')

                <form method="POST" action="{{ route('contact.store') }}">
                    @csrf

                    <div class="field is-horizontal">
                        <div class="field-label">
                            <label class="label">{{ __('Name') }}</label>
                        </div>
                        <div class="field-body">
                            <div class="field">
                                <div class="control">
                                    <input class="input" id="name" type="text" name="name" value="{{ old('name') }}"
                                           placeholder="{{ __('Name') }}" required autofocus>
                                </div>
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
                                <div class="control">
                                    <input class="input" id="email" type="text" name="email" value="{{ old('email') }}"
                                           placeholder="{{ __('Email') }}" required autofocus>
                                </div>
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
                                <textarea class="textarea" id="message" type="text" name="message"
                                          rows="10"
                                          placeholder="{{ __('Your message here') }}">{{ old('message') }}</textarea>
                                </div>
                                @include('layouts.partials._form_errors', ['data' => 'message'])
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
    </section>
@endsection

@section('js')
    {!! NoCaptcha::renderJs() !!}
@endsection
