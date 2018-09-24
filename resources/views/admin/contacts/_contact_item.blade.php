<tr>
    <td width="5%">
        <span class="icon">
               <i class="far fa-address-book"></i>
        </span>
        {{ $contact->name }}
        <a href="{{ 'mailto:' . $contact->email }}">{{ $contact->email }}</a>
    </td>
    <td>
        <a href="{{ route('admin.contacts.show', ['contact' => $contact]) }}">{{ $contact->message }}</a>
    </td>
    <td>
        <form action="{{ route('admin.contacts.destroy', ['contact' => $contact]) }}" method="POST">
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