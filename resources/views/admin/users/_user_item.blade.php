<tr>
    <td>
        <a href="{{ route('admin.users.show', compact('user')) }}">{{ $user->name }}</a>
    </td>
    <td>

    </td>
    <td>
        @if (isset($user->cosplayer))
            <a href="{{ route('admin.cosplayers.show', ['cosplayer' => $user->cosplayer]) }}">
                <span class="icon has-text-info">
                    <i class="fas fa-link"></i>
                </span>
            </a>
        @endif
    </td>
    <td>
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
    <td>
        @include('admin.partials._edit_delete_buttons', ['route' => route('admin.users.edit', compact('user')), 'key' => $user->id])

    </td>
</tr>