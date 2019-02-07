<tr>
    <td>
        <a href="{{ route('admin.users.show', compact('user')) }}">{{ $user->name }}</a>
    </td>
    <td width="2%">
        @canImpersonate
        @canBeImpersonated($user)
        <a href="{{ route('impersonate', ['user' => $user->id]) }}">
                        <span class="icon has-text-info">
                            <i class="fas fa-sign-in-alt"></i>
                        </span>
        </a>
        @endCanBeImpersonated
        @endCanImpersonate
    </td>
    <td width="2%">
        @if (isset($user->cosplayer))
            <a href="{{ route('admin.cosplayers.show', ['cosplayer' => $user->cosplayer]) }}">
                <span class="icon has-text-info">
                    <i class="fas fa-link"></i>
                </span>
            </a>
        @endif
    </td>
    <td width="2%">
        <a href="{{ route('admin.users.edit', compact('user')) }}">
            <span class="icon has-text-info">
                <i class="far fa-edit"></i>
            </span>
        </a>
    </td>
    <td width="2%">
        <a class="button modal-button is-danger is-inverted is-small" data-target="modal-delete-{{ $user->id }}">
            <span class="icon has-text-danger">
                <i class="fas fa-trash-alt"></i>
            </span>
        </a>
    </td>
</tr>