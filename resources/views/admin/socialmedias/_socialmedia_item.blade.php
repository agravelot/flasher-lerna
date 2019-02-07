<tr>
    <td>{{ $socialMedia->name }}</td>
    <td width="2%">
        <a href="{{ route('admin.social-medias.edit', compact('socialMedia')) }}">
            <span class="icon has-text-info">
                <i class="far fa-edit"></i>
            </span>
        </a>
    </td>
    <td width="2%">
        <a class="button modal-button is-danger is-inverted is-small" data-target="modal-delete-{{ $socialMedia->id }}">
            <span class="icon has-text-danger">
                <i class="fas fa-trash-alt"></i>
            </span>
        </a>
    </td>
</tr>