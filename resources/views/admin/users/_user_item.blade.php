<tr>
    <td width="5%">
        <span class="icon is-small">
            <i class="fas fa-user"></i>
        </span>
    </td>
    <td>
        <a href="{{ route('admin.users.show', ['user' => $user]) }}">{{ $user->name }}</a>
    </td>
    <td>
        <a href="{{ route('admin.users.edit', ['user' => $user]) }}">
            <span class="icon has-text-info">
                <i class="far fa-edit"></i>
            </span>
        </a>
    </td>
    <td>
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