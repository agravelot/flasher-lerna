@extends('layouts.app')

@section('content')

    <div class="columns is-marginless is-centered">
        <div class="column is-5">
            <div class="card">
                <header class="card-header">
                    <p class="card-header-title">{{ __('Reset Password') }}</p>
                </header>

                <div class="card-content">
                    @if (session('status'))
                        <div class="notification">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="forgot-password-form" method="POST" action="{{ route('password.email') }}">

                        @csrf

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

                        <div class="field is-horizontal">
                            <div class="field-label"></div>

                            <div class="field-body">
                                <div class="field is-grouped">
                                    <div class="control">
                                        <button type="submit" class="button is-primary">
                                            {{ __('Send Password Reset Link') }}
                                        </button>
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
