<tr>
    <td>
        <a href="{{ route('admin.categories.show', ['contact' => $category]) }}">{{ $category->name }}</a>
    </td>
    <td></td>
    <td>
        @include('admin.partials._edit_delete_buttons', ['route' => route('admin.categories.edit', compact('category')), 'key' => $category->id])
    </td>
</tr>