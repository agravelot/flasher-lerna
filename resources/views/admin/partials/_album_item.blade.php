<tr>
    <td width="5%">
        <i class="far fa-images"></i>
        {{ $album->pictures->count() }}
    </td>
    <td>
        <a href="{{ route('albums.show', ['album' => $album]) }}">{{ $album->title }}</a>
    </td>
    <td><a class="button is-small is-primary"
           href="{{ route('albums.edit', ['album' => $album]) }}">Update</a>
    <td>
        <form action="{{ route('albums.destroy', ['album' => $album]) }}"
              method="POST">
            {{ method_field('DELETE') }}
            @csrf
            <button class="button is-small is-danger">Delete</button>
        </form>
    </td>
</tr>