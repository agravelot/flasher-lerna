@extends('layouts.app')

@section('pageTitle', __('Contact me'))

@section('content')
    <section class="section">
        <div class="container">
            <div class="columns">
                <div class="column">

                    <h2 class="title is-3">Comment me contacter</h2>

                    <div class="content">
                        <p>
                            Par téléphone au <a href="tel:0766648588">07 66 64 85 88</a>.
                        </p>
                        <p>
                            Ou bien en remplissant le formulaire de contact.
                        </p>
                    </div>

                </div>
                <div class="column">
                    <h2 class="title is-3">Formulaire de contact</h2>
                    @include('layouts.partials._messages')

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf

                        <div class="field">
                            <label class="label">{{ __('Name') }}</label>
                            <div class="control">
                                <input class="input" id="name" type="text" name="name" value="{{ old('name') }}"
                                       placeholder="{{ __('Name') }}" required autofocus>
                            </div>
                            @include('layouts.partials._form_errors', ['data' => 'name'])
                        </div>

                        <div class="field">
                            <label class="label">{{ __('Email') }}</label>
                            <div class="control">
                                <input class="input" id="email" type="text" name="email"
                                       value="{{ old('email') }}"
                                       placeholder="{{ __('Email') }}" required autofocus>
                            </div>
                            @include('layouts.partials._form_errors', ['data' => 'email'])
                        </div>

                        <div class="field">
                            <label class="label">{{ __('Message') }}</label>
                            <div class="control">
                                <textarea class="textarea" id="message" type="text" name="message"
                                          rows="10"
                                          placeholder="{{ __('Your message here') }}">{{ old('message') }}</textarea>
                            </div>
                            @include('layouts.partials._form_errors', ['data' => 'message'])
                        </div>

                        <div class="field">
                            <div class="control">
                                {!! NoCaptcha::display() !!}
                            </div>
                            @include('layouts.partials._form_errors', ['data' => 'g-recaptcha-response'])
                        </div>

                        <div class="field">
                            <div class="control">
                                <button class="button is-primary">
                                    {{ __('Send') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    @parent
    {!! NoCaptcha::renderJs() !!}
@endsection
