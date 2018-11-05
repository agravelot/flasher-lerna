<tr>
    <td width="2%">
        <span class="icon is-small">
            <span class="has-margin-sm">{{ $album->media->count() }}</span>
            <i class="far fa-images"></i>
        </span>
    </td>
    <td width="2%">
        @if ($album->publish)
            <span class="icon is-small has-text-success">
                <i class="fas fa-check"></i>
            </span>
        @endif
    </td>
    <td width="2%">
        @if ($album->password)
            <span class="icon is-small has-text-warning">
                <i class="fas fa-lock"></i>
            </span>
        @endif
    </td>
    <td>
        <a href="{{ route('admin.albums.show', ['album' => $album]) }}">{{ $album->title }}</a>
    </td>
    <td width="2%">
        <a href="{{ route('admin.albums.edit', ['album' => $album]) }}">
            <span class="icon has-text-info">
                <i class="far fa-edit"></i>
            </span>
        </a>
    </td>
    <td width="2%">
        <form action="{{ route('admin.albums.destroy', ['album' => $album]) }}" method="POST">
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