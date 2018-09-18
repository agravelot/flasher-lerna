<tr>
    <td width="5%">
        <span class="icon is-small">
            <i class="fas fa-user"></i>
        </span>
    </td>
    <td>
        <a href="{{ route('admin.users.show', ['user' => $user]) }}">{{ $user->name }}</a>
    </td>
    @canImpersonate
        @canBeImpersonated($user)
            <td width="2%">
                <a href="{{ route('impersonate', ['user' => $user->id]) }}">
                    <span class="icon has-text-info">
                        <i class="fas fa-sign-in-alt"></i>
                    </span>
                </a>
            </td>
        @endCanBeImpersonated
    @endCanImpersonate
    <td width="2%">
        <a href="{{ route('admin.users.edit', ['user' => $user]) }}">
            <span class="icon has-text-info">
                <i class="far fa-edit"></i>
            </span>
        </a>
    </td>
    <td width="2%">
        <form action="{{ route('admin.users.destroy', ['user' => $user]) }}"
              method="POST">
            {{ method_field('DELETE') }}
            @csrf
            <button class="button is-danger is-outlined is-small">
                <span class="icon has-text-danger">
                    <i class="far fa-trash-alt"></i>
                </span>
            </button>

        </form>
    </td>
</tr>