<tr>
    <td>
        {{ $contact->name }}
        <a href="{{ 'mailto:' . $contact->email }}">{{ $contact->email }}</a>
    </td>
    <td>
        <a href="{{ route('admin.contacts.show', compact('contact')) }}">{{ str_limit($contact->message, 150) }}</a>
    </td>
    <td>
        @include('admin.partials._delete_button', ['key' => $contact->id])
    </td>
</tr>