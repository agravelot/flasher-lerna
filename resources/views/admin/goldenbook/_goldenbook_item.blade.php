<tr>
    <td>
        <p>{{ $goldenBookPost->name }}</p>
        <a href="{{ 'mailto:' . $goldenBookPost->email }}">{{ $goldenBookPost->email }}</a>
    </td>
    <td>
        <p>{{ $goldenBookPost->body }}</p>
    </td>

    <td >
        @if ($goldenBookPost->isPublished())
            <form action="{{ route('admin.published-goldenbook.destroy', compact('goldenBookPost')) }}" method="POST">
                {{ method_field('DELETE') }}
                @csrf
                <button type="submit" class="button is-rounded is-success is-small">
                    <span>Published</span>
                </button>
            </form>
        @else
            <form action="{{ route('admin.published-goldenbook.store') }}" method="POST">
                @csrf
                <input type="hidden" name="goldenbook_id" value="{{ $goldenBookPost->id }}">
                <button type="submit" class="button is-rounded is-warning is-small">
                    <span>Unpublished</span>
                </button>
            </form>
        @endif
    </td>

    <td width="2%">
        <form action="{{ route('admin.goldenbook.destroy',compact('goldenBookPost')) }}" method="POST">
            {{ method_field('DELETE') }}
            @csrf
            <button type="submit" class="button is-danger is-inverted is-small">
                <span class="icon has-text-danger">
                    <i class="far fa-trash-alt"></i>
                </span>
            </button>
        </form>
    </td>
</tr>