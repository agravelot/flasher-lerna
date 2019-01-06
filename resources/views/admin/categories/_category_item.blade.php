<tr>
    <td>
        <a href="{{ route('admin.categories.show', ['contact' => $category]) }}">{{ $category->name }}</a>
    </td>
    <td width="2%">
        <a href="{{ route('admin.categories.edit', ['category' => $category]) }}">
            <span class="icon has-text-info">
                <i class="far fa-edit"></i>
            </span>
        </a>
    </td>
    <td width="2%">
        <form action="{{ route('admin.categories.destroy', ['category' => $category]) }}" method="POST">
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