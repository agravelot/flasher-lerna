<tr>
    <td width="5%">
        <span class="icon is-small">
            <i class="fas fa-user-tag"></i>
        </span>
    </td>
    <td>
        <a href="{{ route('admin.cosplayers.show', ['cosplayer' => $cosplayer]) }}">{{ $cosplayer->name }}</a>
    </td>
    <td width="2%">
        <a href="{{ route('admin.cosplayers.edit', ['cosplayer' => $cosplayer]) }}">
            <span class="icon has-text-info">
                <i class="far fa-edit"></i>
            </span>
        </a>
    </td>
    <td width="2%">
        <form action="{{ route('admin.cosplayers.destroy', ['cosplayer' => $cosplayer]) }}" method="POST">
            {{ method_field('DELETE') }}
            @csrf
            <button class="button is-danger is-inverted is-small">
                <span class="icon has-text-danger">
                    <i class="far fa-trash-alt"></i>
                </span>
            </button>

        </form>
    </td>
</tr>