<tr>
    <td>
        <a href="{{ route('admin.cosplayers.show', compact('cosplayer')) }}">{{ $cosplayer->name }}</a>
    </td>
    <td></td>
    <td>
        @include('admin.partials._edit_delete_buttons', ['route' => route('admin.cosplayers.edit', compact('cosplayer')), 'key' => $cosplayer->id])
    </td>
</tr>