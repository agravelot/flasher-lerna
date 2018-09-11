<tr>
    <td width="5%">
        <span class="icon">
               <i class="far fa-address-book"></i>
        </span>
        {{ $contact->name }}
        {{ $contact->email }}
    </td>
    <td>
        <a href="{{ route('contact.show', ['contact' => $contact]) }}">{{ $contact->message }}</a>
    </td>
    <td>
        <form action="{{ route('contact.destroy', ['contact' => $contact]) }}"
              method="POST">
            {{ method_field('DELETE') }}
            @csrf
            <button class="button is-danger is-outlined is-small">
                <span class="icon has-text-danger">
                    <i class="far fa-trash-alt"></i>
                </span>
            </button>

        </form>
    </td>
</tr>