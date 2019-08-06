<tr>
    <td>
        <p>{{ $goldenBookPost->name }}</p>
        <a href="{{ 'mailto:' . $goldenBookPost->email }}">{{ $goldenBookPost->email }}</a>
    </td>
    <td>
        <p>{{ $goldenBookPost->body }}</p>
    </td>
    <td>
        @if ($goldenBookPost->isPublished())
            <form action="{{ route('admin.published-testimonials.destroy', compact('goldenBookPost')) }}" method="POST">
                {{ method_field('DELETE') }}
                @csrf
                <button type="submit" class="button is-rounded is-success is-small">
                    <span>Published</span>
                </button>
            </form>
        @else
            <form action="{{ route('admin.published-testimonials.store') }}" method="POST">
                @csrf
                <input type="hidden" name="goldenbook_id" value="{{ $goldenBookPost->id }}">
                <button type="submit" class="button is-rounded is-warning is-small">
                    <span>Unpublished</span>
                </button>
            </form>
        @endif
    </td>
    <td>
        @include('admin.partials._delete_button', ['key' => $goldenBookPost->id])
    </td>
</tr>
