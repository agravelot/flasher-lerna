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
        <form action="{{ route('admin.social-medias.destroy', compact('socialMedia')) }}" method="POST">
            {{ method_field('DELETE') }}
            @csrf
            <button class="button is-danger is-inverted is-small">
                <span class="icon has-text-danger">
                    <i class="fas fa-trash-alt"></i>
                </span>
            </button>

        </form>
    </td>
</tr>