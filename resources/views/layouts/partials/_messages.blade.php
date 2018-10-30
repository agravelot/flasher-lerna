@foreach (['danger', 'warning', 'success', 'info'] as $msg)

    @foreach ($errors->all() as $error)
        <p class="notification is-danger">
            <button class="delete"></button>
            {{ $error }}
        </p>
    @endforeach

    @if(Session::has($msg))
        <p class="notification is-{{ $msg }}">
            <button class="delete"></button>
            {{ Session::get($msg) }}
        </p>
    @endif
@endforeach