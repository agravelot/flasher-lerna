@if ($errors->has($data))
    <p class="help is-danger">
        {{ $errors->first($data) }}
    </p>
@endif