<div class="card-content">
    <form class="register-form" method="POST" action="{{ $route }}">

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
                        <input class="input" id="name" type="name" name="name"
                               value="{{ old('name', isset($user->name) ? $user->name : null) }}"
                               required autofocus>
                    </p>
                    @include('layouts.partials._form_errors', ['data' => 'name'])
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
                               value="{{ old('email', isset($user->email) ? $user->email : null) }}" required autofocus>
                    </p>
                    @include('layouts.partials._form_errors', ['data' => 'email'])
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
                        <input class="input" id="password" type="password" name="password" @if (!isset($user)) required @endif>
                    </p>
                    @include('layouts.partials._form_errors', ['data' => 'password'])
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
                               name="password_confirmation" @if (!isset($user)) required @endif>
                    </p>
                    @include('layouts.partials._form_errors', ['data' => 'password_confirmation'])
                </div>
            </div>
        </div>


        <div class="field is-horizontal">
            <div class="field-label">
                <label class="label">Cosplayer</label>
            </div>

            <div class="field-body">
                <div class="field">
                    <div class="control has-icons-left">
                        <div class="select">
                            <select name="cosplayer" id="cosplayer">
                                <option value=""> None</option>
                                @foreach($cosplayers as $cosplayer )
                                    @php
                                        $options = null;
                                        if (isset($user->cosplayer) && $user->cosplayer->id === $cosplayer->id) {
                                            $options = 'selected';
                                        } elseif (isset($cosplayer->user)) {
                                            $options = 'disabled';
                                        }
                                    @endphp
                                    <option value="{{ $cosplayer->id }}" {{ $options }}>{{ $cosplayer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="icon is-medium is-left">
                            <i class="fas fa-user-tag"></i>
                        </span>
                    </div>
                    @include('layouts.partials._form_errors', ['data' => 'cosplayer'])
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