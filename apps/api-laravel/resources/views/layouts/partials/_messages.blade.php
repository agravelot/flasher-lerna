@if ($errors->first())
    <p class="notification is-danger">
        <button class="delete"></button>
        {{ $errors->first() }}
    </p>
@endif

@foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(Session::has($msg))
        <p class="notification is-{{ $msg }}">
            <button class="delete"></button>
            {{ Session::get($msg) }}
        </p>
    @endif
@endforeach