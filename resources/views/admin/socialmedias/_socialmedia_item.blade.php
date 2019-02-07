<tr>
    <td>{{ $socialMedia->name }}</td>
    <td></td>
    <td>
        @include('admin.partials._edit_delete_buttons', ['route' => route('admin.social-medias.edit', compact('socialMedia')), 'key' => $socialMedia->id])
    </td>
</tr>