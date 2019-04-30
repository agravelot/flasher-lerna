@extends('layouts.app')

@section('pageTitle', __('Reset Password'))

@section('content')

    <section class="hero is-primary">
        <div class="hero-body">
            <div class="container">
                <h1 class="title">
                    {{ __('Reset Password') }}
                </h1>
            </div>
        </div>
    </section>


    <div class="columns is-marginless is-centered">
        <div class="column is-5">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">{{ __('Reset Password') }}</p>
                </header>

                <div class="card-content">
                    @if (session('status'))
                        <div class="notification is-info">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="password-reset-form" method="POST" action="{{ route('password.request') }}">

                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">


                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">{{ __('E-Mail Address') }}</label>
                            </div>

                            <div class="field-body">
                                <div class="field">
                                    <p class="control has-icons-left">
                                        <input class="input" id="email" type="email" name="email"
                                               value="{{ old('email') }}" required autofocus>
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                    </p>
                                    @include('layouts.partials._form_errors', ['data' => 'email'])
                                </div>
                            </div>
                        </div>

                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">{{ __('Password') }}</label>
                            </div>

                            <div class="field-body">
                                <div class="field">
                                    <p class="control has-icons-left">
                                        <input class="input" id="password" type="password" name="password" required>
                                        <span class="icon is-small is-left">
                                            <i class="fas fa-key"></i>
                                        </span>
                                    </p>
                                    @include('layouts.partials._form_errors', ['data' => 'password'])
                                </div>
                            </div>
                        </div>


                        <div class="field is-horizontal">
                            <div class="field-label">
                                <label class="label">{{ __('Confirm Password') }}</label>
                            </div>

                            <div class="field-body">
                                <div class="field">
                                    <p class="control">
                                        <input class="input" id="password-confirm" type="password"
                                               name="password_confirmation" required>
                                    </p>
                                    @include('layouts.partials._form_errors', ['data' => 'password_confirmation'])
                                </div>
                            </div>
                        </div>


                        <div class="field is-horizontal">
                            <div class="field-label"></div>

                            <div class="field-body">
                                <div class="field is-grouped">
                                    <div class="control">
                                        <button type="submit" class="button is-primary">{{ __('Reset Password') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
