<tr>
    <td width="5%">
        <span class="icon is-small">
            <i class="far fa-images"></i>
            {{ $album->pictures->count() }}
        </span>
    </td>
    <td>
        <a href="{{ route('albums.show', ['album' => $album]) }}">{{ $album->title }}</a>
    </td>
    <td>
        <a href="{{ route('admin.albums.edit', ['album' => $album]) }}">
            <span class="icon has-text-info">
                <i class="far fa-edit"></i>
            </span>
        </a>
    </td>
    <td>
        <form action="{{ route('admin.albums.destroy', ['album' => $album]) }}"
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