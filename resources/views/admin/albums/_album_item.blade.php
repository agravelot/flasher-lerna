<tr>
    <td>
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
    <td></td>
    <td>
        <span class="icon is-small">
            <span class="has-margin-sm has-text-grey">{{ $album->media->count() }}</span>
            <i class="fas fa-images has-text-grey-light"></i>
        </span>
    </td>
    <td>
        @include('admin.partials._edit_delete_buttons', ['route' => route('admin.albums.edit', compact('album')), 'key' => $album->id])
    </td>
</tr>

