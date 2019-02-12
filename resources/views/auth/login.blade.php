@extends('layouts.app')

@section('content')

    <div class="container has-text-centered">
        <div class="column is-one-third is-offset-4">
            <h1 class="title has-text-grey">{{ __('Login') }}</h1>
            <p class="subtitle has-text-grey">{{ __('Please login to proceed.') }}</p>
            <div class="box">
                <form class="login-form" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="field">
                        <div class="control has-icons-left">
                            <input class="input" id="email" type="email" name="email"
                                   placeholder="{{ __('Email') }}" autofocus="" value="{{ old('email') }}">
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                        @include('layouts.partials._form_errors', ['data' => 'email'])
                    </div>

                    <div class="field">
                        <div class="control has-icons-left">
                            <input class="input" id="password" type="password" name="password"
                                   placeholder="{{ __('Your Password') }}">
                            <span class="icon is-small is-left">
                                <i class="fas fa-key"></i>
                            </span>
                        </div>
                        @include('layouts.partials._form_errors', ['data' => 'password'])
                    </div>
                    <div class="field">
                        <label class="checkbox">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            {{ __('Remember me') }}
                        </label>
                    </div>
                    <button class="button is-block is-primary is-large is-fullwidth" type="submit">{{ __('Login') }}</button>
                </form>
            </div>
            <p class="has-text-grey">
                <a href="{{ route('register') }}">{{ __('Sign Up') }}</a> &nbsp;·&nbsp;
                <a href="{{ route('password.request') }}">{{ __('Forgot Password') }}</a>
            </p>
        </div>
    </div>

@endsection
