<tr>
    <td width="5%">
        <i class="far fa-images"></i>
        {{ $contact->name }}
        {{ $contact->email }}
    </td>
    <td>
        <a href="{{ route('contact.show', ['album' => $contact]) }}">{{ $contact->message }}</a>
    </td>
    <td><a class="button is-small is-primary"
           href="{{ route('contact.edit', ['album' => $contact]) }}">Update</a>
    <td>
        <form action="{{ route('contact.destroy', ['album' => $contact]) }}"
              method="POST">
            {{ method_field('DELETE') }}
            @csrf
            <button class="button is-small is-danger">Delete</button>
        </form>


    </td>
</tr>