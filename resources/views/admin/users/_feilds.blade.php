{{--<form method="POST" enctype="multipart/form-data" action="{{ $route }}">--}}
    {{--@csrf--}}

    {{--@if (isset($user))--}}
        {{--{{ method_field('PATCH') }}--}}
    {{--@endif--}}

    {{--<div class="field is-horizontal">--}}
        {{--<div class="field-label">--}}
            {{--<label class="label">Title</label>--}}
        {{--</div>--}}

        {{--<div class="field-body">--}}
            {{--<div class="field">--}}
                {{--<p class="control">--}}
                    {{--<input class="input" id="name" type="text" name="name"--}}
                           {{--value="{{ old('name', isset($user->name) ? $user->name : null) }}"--}}
                           {{--required autofocus>--}}
                {{--</p>--}}

                {{--@if ($errors->has('name'))--}}
                    {{--<p class="help is-danger">--}}
                        {{--{{ $errors->first('name') }}--}}
                    {{--</p>--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}

    {{--<div class="field is-horizontal">--}}
        {{--<div class="field-label">--}}
            {{--<!-- Left empty for spacing -->--}}
        {{--</div>--}}
        {{--<div class="field-body">--}}
            {{--<div class="field">--}}
                {{--<div class="control">--}}
                    {{--<button class="button is-primary">--}}
                        {{--Send--}}
                    {{--</button>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</form>--}}



<div class="card-content">
    <form class="register-form" method="POST" action="{{ $route  }}">

        @csrf

        @if (isset($user))
            {{ method_field('PATCH') }}
        @endif

        <div class="field is-horizontal">
            <div class="field-label">
                <label class="label">Name</label>
            </div>

            <div class="field-body">
                <div class="field">
                    <p class="control">
                        <input class="input" id="name" type="name" name="name" value="{{ old('name') }}"
                               required autofocus>
                    </p>

                    @if ($errors->has('name'))
                        <p class="help is-danger">
                            {{ $errors->first('name') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="field is-horizontal">
            <div class="field-label">
                <label class="label">E-mail Address</label>
            </div>

            <div class="field-body">
                <div class="field">
                    <p class="control">
                        <input class="input" id="email" type="email" name="email"
                               value="{{ old('email') }}" required autofocus>
                    </p>

                    @if ($errors->has('email'))
                        <p class="help is-danger">
                            {{ $errors->first('email') }}
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
                        <input class="input" id="password" type="password" name="password" required>
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
                <label class="label">Confirm Password</label>
            </div>

            <div class="field-body">
                <div class="field">
                    <p class="control">
                        <input class="input" id="password-confirm" type="password"
                               name="password_confirmation" required>
                    </p>
                </div>
            </div>
        </div>

        <div class="field is-horizontal">
            <div class="field-label"></div>

            <div class="field-body">
                <div class="field is-grouped">
                    <div class="control">
                        <button type="submit" class="button is-primary">Register</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>