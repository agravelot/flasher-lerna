@component('mail::message')
# Bonjour !

From : {{ $contact->name }} <{{ $contact->email }}>

Informations du contact : 

{!! $contact->message !!}

Cordialement,
Flasher
@endcomponent
