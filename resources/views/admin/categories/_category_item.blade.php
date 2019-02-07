<tr>
    <td>
        <a href="{{ route('admin.categories.show', ['contact' => $category]) }}">{{ $category->name }}</a>
    </td>
    <td width="2%">
        <a href="{{ route('admin.categories.edit', compact('category')) }}">
            <span class="icon has-text-info">
                <i class="far fa-edit"></i>
            </span>
        </a>
    </td>
    <td width="2%">
        <a class="button modal-button is-danger is-inverted is-small" data-target="modal-delete-{{ $category->id }}">
            <span class="icon has-text-danger">
                <i class="fas fa-trash-alt"></i>
            </span>
        </a>
    </td>
</tr>