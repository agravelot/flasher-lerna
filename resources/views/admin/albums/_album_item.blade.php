<tr>
    <td width="2%">
        @if (!$album->isPasswordLess())
            <span class="icon is-small has-text-warning">
                <i class="fas fa-lock"></i>
            </span>
        @elseif ($album->isPublished())
            <span class="icon is-small has-text-success">
                <i class="fas fa-check"></i>
            </span>
        @else
            <span class="icon is-small has-text-grey-light">
                <i class="far fa-sticky-note"></i>
            </span>
        @endif
    </td>
    <td>
        <a href="{{ route('admin.albums.show', compact('album')) }}">{{ $album->title }}</a>
    </td>
    <td width="5%">
        <span class="icon is-small">
            <span class="has-margin-sm has-text-grey">{{ $album->media->count() }}</span>
            <i class="fas fa-images has-text-grey-light"></i>
        </span>
    </td>
    <td width="2%">
        <a href="{{ route('admin.albums.edit', compact('album')) }}">
            <span class="icon has-text-info">
                <i class="fas fa-edit"></i>
            </span>
        </a>
    </td>
    <td width="2%">
        <a class="button modal-button is-danger is-inverted is-small" data-target="modal-delete-{{ $album->id }}">
            <span class="icon has-text-danger">
                <i class="fas fa-trash-alt"></i>
            </span>
        </a>
    </td>
</tr>