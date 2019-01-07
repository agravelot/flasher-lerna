<tr>
    <td>
        <p>{{ $goldenBookPost->body }}</p>
    </td>

    <td width="2%">
        <form action="{{ route('admin.goldenbook.destroy', ['goldenBookPost' => $goldenBookPost]) }}" method="POST">
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