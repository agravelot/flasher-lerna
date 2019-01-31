<tr>
    <td>
        <a href="{{ route('admin.cosplayers.show', compact('cosplayer')) }}">{{ $cosplayer->name }}</a>
    </td>
    <td width="2%">
        <a href="{{ route('admin.cosplayers.edit', compact('cosplayer')) }}">
            <span class="icon has-text-info">
                <i class="far fa-edit"></i>
            </span>
        </a>
    </td>
    <td width="2%">
        <form action="{{ route('admin.cosplayers.destroy', compact('cosplayer')) }}" method="POST">
            {{ method_field('DELETE') }}
            @csrf
            <button class="button is-danger is-inverted is-small">
                <span class="icon has-text-danger">
                    <i class="fas fa-trash-alt"></i>
                </span>
            </button>

        </form>
    </td>
</tr>