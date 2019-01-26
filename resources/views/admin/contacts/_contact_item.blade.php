<tr>
    <td width="5%">
        {{ $contact->name }}
        <a href="{{ 'mailto:' . $contact->email }}">{{ $contact->email }}</a>
    </td>
    <td>
        <a href="{{ route('admin.contacts.show', compact('contact')) }}">{{ str_limit($contact->message, 150) }}</a>
    </td>
    <td>
        <form action="{{ route('admin.contacts.destroy', compact('contact')) }}" method="POST">
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