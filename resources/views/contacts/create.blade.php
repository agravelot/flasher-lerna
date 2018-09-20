@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card-content">

            <h1 class="title has-text-centered">Contact me</h1>

            @include('layouts.partials._messages')

            <form method="POST" enctype="multipart/form-data" action="{{ route('contact.store') }}">
                @csrf

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

                           @include('layouts.partials._form_errors', ['data' => 'name'])
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

                            @include('layouts.partials._form_errors', ['data' => 'email'])
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
