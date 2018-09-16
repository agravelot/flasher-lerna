<tr>
    <td width="5%">
        <span class="icon is-small">
            <i class="far fa-images"></i>
        </span>
    </td>
    <td>
        <a href="{{ route('cosplayers.show', ['cosplayer' => $cosplayer]) }}">{{ $cosplayer->name }}</a>
    </td>
    <td>
        <a href="{{ route('admin.cosplayers.edit', ['cosplayer' => $cosplayer]) }}">
            <span class="icon has-text-info">
                <i class="far fa-edit"></i>
            </span>
        </a>
    </td>
    <td>
        <form action="{{ route('admin.cosplayers.destroy', ['cosplayer' => $cosplayer]) }}"
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