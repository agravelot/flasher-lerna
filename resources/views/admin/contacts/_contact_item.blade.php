<tr>
    <td width="5%">
        {{ $contact->name }}
        <a href="{{ 'mailto:' . $contact->email }}">{{ $contact->email }}</a>
    </td>
    <td>
        <a href="{{ route('admin.contacts.show', compact('contact')) }}">{{ str_limit($contact->message, 150) }}</a>
    </td>
    <td>
        <a class="button modal-button is-danger is-inverted is-small" data-target="modal-delete-{{ $contact->id }}">
            <span class="icon has-text-danger">
                <i class="fas fa-trash-alt"></i>
            </span>
        </a>
    </td>
</tr>