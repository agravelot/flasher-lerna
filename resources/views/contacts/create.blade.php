@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card-content">

            <h1 class="title has-text-centered">Contact me</h1>

            <form method="POST" enctype="multipart/form-data" action="{{ route('contact.store') }}">
                {{ csrf_field() }}

                <div class="field is-horizontal">
                    <div class="field-label">
                        <label class="label">Name</label>
                    </div>

                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <input class="input" id="name" type="text" name="name" value="{{ old('name') }}"
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
                        <label class="label">Email</label>
                    </div>

                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <input class="input" id="email" type="text" name="email" value="{{ old('email') }}"
                                       required autofocus>
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
                        <label class="label">Message</label>
                    </div>

                    <div class="field-body">
                        <div class="field">
                            <p class="control">
                                <textarea class="textarea" id="message" type="text" name="message" rows="10">{{ old('message') }}</textarea>
                            </p>

                            @if ($errors->has('message'))
                                <p class="help is-danger">
                                    {{ $errors->first('message') }}
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
