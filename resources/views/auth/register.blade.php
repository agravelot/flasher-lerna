@extends('layouts.app')

@section('pageTitle', __('Register'))

@section('content')

    @include('layouts.partials._messages')

    <div class="container has-text-centered">
        <div class="column is-half is-offset-3">
            <h1 class="title has-text-grey">{{ __('Register') }}</h1>
            <p class="subtitle has-text-grey">{{ __('Please register to proceed.') }}</p>
            <div class="box">
                <form class="register-form" method="POST" action="{{ route('register') }}">

                    @csrf

                    <div class="field">
                        <p class="control has-icons-left">
                            <input class="input" id="name" type="name" name="name" value="{{ old('name') }}"
                                   placeholder="{{ __('Name') }}" required autofocus>
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                        </p>
                        @include('layouts.partials._form_errors', ['data' => 'name'])
                    </div>


                    <div class="field">
                        <p class="control has-icons-left">
                            <input class="input" id="email" type="email" name="email"
                                   value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autofocus>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </p>
                        @include('layouts.partials._form_errors', ['data' => 'email'])
                    </div>


                    <div class="field">
                        <p class="control has-icons-left">
                            <input class="input" id="password" type="password" name="password" placeholder="{{ __('Password') }}"
                                   required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-key"></i>
                            </span>
                        </p>

                        @include('layouts.partials._form_errors', ['data' => 'password'])
                    </div>

                    <div class="field">
                        <p class="control has-icons-left">
                            <input class="input" id="password-confirm" type="password"
                                   name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-key"></i>
                            </span>
                        </p>
                        @include('layouts.partials._form_errors', ['data' => 'password_confirmation'])
                    </div>

                    <div class="field">
                        <p class="control">
                            {!! NoCaptcha::display() !!}
                        </p>
                        @include('layouts.partials._form_errors', ['data' => 'g-recaptcha-response'])
                    </div>

                    <button class="button is-block is-primary is-large is-fullwidth" type="submit">{{ __('Register') }}</button>
                </form>

            </div>
            <p class="has-text-grey">
                <a href="{{ route('register') }}">{{ __('Login') }}</a> &nbsp;·&nbsp;
                <a href="{{ route('password.request') }}">{{ __('Forgot Password') }}</a>
            </p>
        </div>
    </div>
@endsection

@section('js')
    {!! NoCaptcha::renderJs() !!}
@stop
